# Instaparts ↔ inDrive Integration API

## Sales Performance & Financial Reporting

### Overview

This document defines the proposed API contract for integrating
Instaparts with inDrive to support:

-   Sales performance reporting
-   Financial reporting
-   Order tracking and reconciliation

------------------------------------------------------------------------

## Base URL

https://api.instaparts.org/api/integrations/indrive

------------------------------------------------------------------------

## Authentication

Authorization: Bearer {API_TOKEN}

------------------------------------------------------------------------

## 1. List Orders

GET /orders


## Query Parameters

| Parameter        | Type     | Required | Description |
|-----------------|----------|----------|-------------|
| from            | datetime | Yes      | Start date/time |
| to              | datetime | Yes      | End date/time |
| driver_phone    | string   | No       | Filter by driver phone |
| status          | string   | No       | Filter by order status |
| branch_id       | integer  | No       | Filter by branch |
| payment_method  | string   | No       | Filter by payment method |
| page            | integer  | No       | Pagination page |
| per_page        | integer  | No       | Items per page (max 100) |
| updated_from    | datetime | No       | Incremental sync start |
| updated_to      | datetime | No       | Incremental sync end |

---

## Example Request
GET /orders?from=2026-04-01&to=2026-04-07&driver_phone=201001234567&page=1&per_page=50


---

## Example Response

```json
{
  "data": [
    {
      "order_id": "ORD-10025",
      "external_reference": "IND-77821",
      "status": "delivered",
      "created_at": "2026-04-05T10:30:00Z",
      "delivered_at": "2026-04-05T12:10:00Z",
      "driver": {
        "name": "Ahmed Samy",
        "phone": "201001234567"
      },
      "customer": {
        "name": "Mohamed Adel",
        "phone": "201099988877"
      },
      "branch": {
        "id": 4,
        "name": "Nasr City"
      },
      "totals": {
        "subtotal": 1200,
        "discount": 100,
        "delivery_fee": 60,
        "tax": 0,
        "total": 1160,
        "currency": "EGP"
      },
      "payment_method": "cash_on_delivery",
      "financials": {
        "collected_amount": 1160,
        "net_settlement": 1080
      }
    }
  ],
  "meta": {
    "page": 1,
    "per_page": 50,
    "total": 245
  }
}
```

------------------------------------------------------------------------

## 2. Order Details

GET /orders/{order_id}

## Example Request
GET /orders/ORD-10025

## Example Response

```json
{
  "order_id": "ORD-10025",
  "external_reference": "IND-77821",
  "status": "delivered",
  "created_at": "2026-04-05T10:30:00Z",
  "confirmed_at": "2026-04-05T10:35:00Z",
  "dispatched_at": "2026-04-05T11:00:00Z",
  "delivered_at": "2026-04-05T12:10:00Z",
  "driver": {
    "id": 98,
    "name": "Ahmed Samy",
    "phone": "201001234567"
  },
  "customer": {
    "id": 551,
    "name": "Mohamed Adel",
    "phone": "201099988877",
    "address": "Nasr City, Cairo"
  },
  "branch": {
    "id": 4,
    "name": "Nasr City"
  },
  "items": [
    {
      "sku": "BRK-001",
      "name": "Brake Pads",
      "qty": 2,
      "unit_price": 500,
      "discount": 50,
      "line_total": 950
    }
  ],
  "totals": {
    "subtotal": 1250,
    "discount": 50,
    "delivery_fee": 60,
    "tax": 0,
    "total": 1260,
    "currency": "EGP"
  },
  "payment_method": "cash_on_delivery",
  "financials": {
    "collected_amount": 1260,
    "driver_fee": 80,
    "platform_fee": 20,
    "net_settlement": 1160
  }
}
```

------------------------------------------------------------------------

## 3. Orders Summary (Optional)

GET /orders-summary

### Query Parameters
| Parameter | Type     | Required |
| --------- | -------- | -------- |
| from      | datetime | Yes      |
| to        | datetime | Yes      |

### Example Response
```json
{
  "total_orders": 245,
  "delivered_orders": 220,
  "cancelled_orders": 25,
  "gross_sales": 250000,
  "discounts": 15000,
  "delivery_fees": 12000,
  "net_sales": 247000,
  "collected_cash": 200000,
  "currency": "EGP"
}
```
------------------------------------------------------------------------

## Notes

-   All timestamps are UTC
-   Pagination required
