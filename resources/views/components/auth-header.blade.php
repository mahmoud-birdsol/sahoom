@props([
    'title',
    'description',
])

<div class="flex w-full flex-col gap-2">
    <h1 style="font-family: 'Playfair Display', serif; font-size: 1.75rem; font-weight: 300; color: #1E2330; line-height: 1.25">{{ $title }}</h1>
    <p style="font-size: 0.8rem; font-weight: 400; color: rgba(30,35,48,.5); line-height: 1.6">{{ $description }}</p>
</div>
