# Kla Tivie Slides API

Kla Tivie Slides API is a WordPress plugin that provides an API for managing slides. This plugin allows you to get slides through a RESTful API.

## Endpoints

### GET `/kla-tivie-slides/v1/by-date`

Retrieve slides filtered by a specific date and meta key.

#### Parameters

- `date` (optional): The date to filter slides by. If not provided, the current date is used.
- `date_key` (optional): The meta key to use for the date comparison. Defaults to `ss_ts_start_date`.

#### Example Request

```sh
curl -X GET "http://your-wordpress-site/wp-json/kla-tivie-slides/v1/by-date?date=2023-10-01&date_key=ss_ts_start_date"
```

#### Example Response

```json
{
    "success": true,
    "requested_date": "2024-10-01T00:00:00+00:00",
    "slides": [
        {
            "id": 1,
            "title": "Slide Title",
            "url": "http://your-wordpress-site/slide/slide-title",
            "dates": {
                "date": "2024-10-01T00:00:00+00:00"
            }
        }
    ],
    "total": 1
}
```