param(
    [ValidateSet('start', 'stop', 'status', 'build')]
    [string]$Action = 'start'
)

$ErrorActionPreference = 'Stop'
$windowsProjectPath = $PSScriptRoot.Replace('\', '/')
if ($windowsProjectPath -notmatch '^([A-Za-z]):/(.*)$') {
    throw 'Le projet doit se trouver sur un disque Windows accessible depuis WSL.'
}
$projectPath = '/mnt/' + $Matches[1].ToLowerInvariant() + '/' + $Matches[2]

function Test-DockerReady {
    $ErrorActionPreference = 'Continue'
    & docker.exe info --format '{{.ServerVersion}}' *> $null
    if ($LASTEXITCODE -ne 0) {
        return $false
    }
    & wsl.exe -d Ubuntu --exec timeout 15 docker info --format '{{.ServerVersion}}' *> $null
    return $LASTEXITCODE -eq 0
}

function Start-LocalDocker {
    if (Test-DockerReady) {
        return
    }

    $dockerDesktopPath = Join-Path $env:ProgramFiles 'Docker\Docker\Docker Desktop.exe'
    if (!(Test-Path -LiteralPath $dockerDesktopPath)) {
        throw 'Docker Desktop est introuvable. Installe-le avant de lancer le projet.'
    }

    Write-Host 'Demarrage de Docker Desktop. Attente du moteur Docker...'
    Start-Process -FilePath $dockerDesktopPath -WindowStyle Hidden
    $deadline = (Get-Date).AddMinutes(3)
    do {
        if (Test-DockerReady) {
            Write-Host 'Docker est pret.'
            return
        }
        Start-Sleep -Seconds 2
    } while ((Get-Date) -lt $deadline)

    throw 'Docker reste inaccessible depuis Ubuntu apres 3 minutes. Consulte Docker Desktop et active Settings > Resources > WSL Integration > Ubuntu, puis relance ce script.'
}

function Invoke-Sail {
    & wsl.exe -d Ubuntu --cd $projectPath --exec bash vendor/bin/sail @args
    if ($LASTEXITCODE -ne 0) {
        throw "La commande Sail a echoue (code $LASTEXITCODE). Consulte le message ci-dessus. Si Docker est inaccessible depuis Ubuntu, active Settings > Resources > WSL Integration > Ubuntu dans Docker Desktop."
    }
}

switch ($Action) {
    'start' {
        Start-LocalDocker
        Invoke-Sail up -d --no-deps mysql redis mailpit laravel.test
        Invoke-Sail npm run dev '--' '--host' '0.0.0.0'
    }
    'stop' {
        Invoke-Sail stop
    }
    'status' {
        Invoke-Sail ps
    }
    'build' {
        Invoke-Sail npm run build
    }
}
