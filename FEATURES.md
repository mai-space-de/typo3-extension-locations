# mai_locations — Feature Reference

Extension key: `mai_locations`
Layer: Feature
Status: 📋 Scaffolded

---

## 1. Location Record

Stores structured venue data in `tx_mailocations_location`.

| Field | Type | Required | Notes |
| --- | --- | --- | --- |
| `name` | string (255) | ✅ | Record label, displayed as heading |
| `street` | string (255) | — | Street address line |
| `zip` | string (20) | — | Postal / ZIP code |
| `city` | string (255) | — | City name |
| `country` | string (255) | — | Country name |
| `phone` | string (50) | — | Rendered as `<a href="tel:…">` |
| `email` | string (255) | — | Rendered as `<a href="mailto:…">` |
| `latitude` | decimal (10,7) | — | WGS-84 latitude; used for map |
| `longitude` | decimal (10,7) | — | WGS-84 longitude; used for map |
| `description` | RTE text | — | Rich-text body, rendered via `f:format.html` |
| `image` | FAL (max 1) | — | Single cover image; `getCoverImage()` returns first or `null` |
| `opening_hours` | inline relation | — | 1-N to `tx_mailocations_opening_hours` |

`hasCoordinates()` returns `true` when latitude or longitude is non-zero.

Default ordering: `sorting ASC` (editor-controlled via drag-and-drop in backend).

---

## 2. Opening Hours Record

Stores time slots in `tx_mailocations_opening_hours`.
Inline child of `tx_mailocations_location` via `parentid` / `parenttable`.

| Field | Type | Required | Notes |
| --- | --- | --- | --- |
| `day_of_week` | select (0–6, special) | — | 0 = Monday … 6 = Sunday; `special` = use `special_date` |
| `time_open` | string (5) | — | Format `HH:MM`, e.g. `09:00` |
| `time_close` | string (5) | — | Format `HH:MM`, e.g. `17:00` |
| `is_closed` | boolean | — | When true, renders "Closed" instead of times |
| `note` | string (255) | — | Optional note appended to the row |
| `special_date` | date | — | Set for bank holidays / one-off exceptions |

`isSpecialDay()` returns `true` when `specialDate !== null`.
Default ordering: `sorting ASC` (editor-controlled drag-and-drop).

### Opening-Hours Frontend Rendering

The `Location/OpeningHours.html` partial iterates over `location.openingHours` and renders a `<ul class="opening-hours">`.

Per-row logic:

| Condition | Day column | Times column |
| --- | --- | --- |
| `slot.isSpecialDay` is true | Formatted date (`d.m.Y` of `specialDate`) | Times or "Closed" |
| Regular day | Translated day name (`dayOfWeek.N` from `locallang.xlf`) | Times or "Closed" |
| `slot.isClosed` is true | (as above) | `opening_hours.closed` label |
| `slot.isClosed` is false | (as above) | `slot.timeOpen` – `slot.timeClose` |

CSS classes:
- `opening-hours` — container `<ul>`
- `opening-hours__row` — each `<li>`
- `opening-hours__row--closed` — modifier added when `slot.isClosed` is true
- `opening-hours__day` — day / date `<span>`
- `opening-hours__times` — times or "Closed" `<span>`
- `opening-hours__note` — optional note `<span>` (only rendered when non-empty)

Translation keys (in `Resources/Private/Language/Default/locallang.xlf`):

| Key | English |
| --- | --- |
| `dayOfWeek.0` | Monday |
| `dayOfWeek.1` | Tuesday |
| `dayOfWeek.2` | Wednesday |
| `dayOfWeek.3` | Thursday |
| `dayOfWeek.4` | Friday |
| `dayOfWeek.5` | Saturday |
| `dayOfWeek.6` | Sunday |
| `opening_hours.closed` | Closed |

---

## 3. Content Element Plugins

| CType | Title | Controller | Action | Cached | FlexForm |
| --- | --- | --- | --- | --- | --- |
| `maispace_locations_list` | Locations List | `LocationController` | `list` | ✅ | ✅ (`LocationsListPlugin.xml`) |
| `maispace_locations_detail` | Location Detail | `LocationController` | `detail` | ✅ | — |

Both are registered in the `maispace_feature` content element group.
Both use the shared `mai-content` icon from `mai_base`.

---

## 4. Frontend Rendering

### `listAction`

Query priority chain:

| Priority | Condition | Query |
| --- | --- | --- |
| 1 | `settings.pages` is set | `LocationRepository::findFromPages($pageUids)` |
| 2 | No pages configured | `LocationRepository::findAll()` |

Template variables:

| Variable | Type | Source |
| --- | --- | --- |
| `locations` | `QueryResultInterface` | Repository query |
| `settings` | array | FlexForm + TypoScript settings |
| `contentObject` | array | `AbstractActionController::getContentObjectData()` |

### `detailAction`

Uses `DetailActionTrait::resolveDetailOrNotFound()` which reads the `location` argument
from the request and redirects to 404 if not found.

Template variables:

| Variable | Type | Source |
| --- | --- | --- |
| `location` | `Location` | Resolved from request argument |
| `settings` | array | FlexForm + TypoScript settings |
| `contentObject` | array | `AbstractActionController::getContentObjectData()` |

The Detail template includes the `Location/OpeningHours` partial when
`location.openingHours` is non-empty and outputs a map `<div>` with
`data-lat` / `data-lng` attributes when `location.hasCoordinates` is true.

---

## 5. Map Integration

The Detail template renders a `<div class="location-detail__map">` with
`data-lat="{location.latitude}"` and `data-lng="{location.longitude}"` when
`location.hasCoordinates` is true. No map library is bundled — downstream
JavaScript activates the map by reading these attributes.

Per the architecture rules, any map that calls external tile servers (e.g. OpenStreetMap)
must be wrapped in a `mai_consent` gate so no third-party request is made before the
visitor grants consent.

---

## 6. FlexForm Configuration

Applies to the List plugin only (`maispace_locations_list`).

| Field | Type | Default | Purpose |
| --- | --- | --- | --- |
| `settings.pages` | group (pages, max 20) | — | Storage page UIDs for record lookup |
| `settings.detailPageUid` | group (pages, max 1) | — | Page UID for detail links |

When `settings.pages` is empty, `LocationRepository::findAll()` uses the
TypoScript `persistence.storagePid` setting instead.

---

## 7. TypoScript Configuration

### Constants (`plugin.tx_mailocations`)

```typoscript
plugin.tx_mailocations {
    view {
        templateRootPath = EXT:mai_locations/Resources/Private/Templates/
        partialRootPath  = EXT:mai_locations/Resources/Private/Partials/
        layoutRootPath   = EXT:mai_locations/Resources/Private/Layouts/
    }
}
plugin.tx_mailocations_list {
    view {
        templateRootPath =
        partialRootPath  =
        layoutRootPath   =
    }
    persistence {
        storagePid =
    }
}
plugin.tx_mailocations_detail {
    view {
        templateRootPath =
        partialRootPath  =
        layoutRootPath   =
    }
    persistence {
        storagePid =
    }
}
```

Path overrides work at priority 10 (base paths at priority 0).
`storagePid` is the fallback when no `settings.pages` is set in FlexForm.

---

## 8. Database Tables

### `tx_mailocations_location`

System columns: `uid`, `pid`, `tstamp`, `crdate`, `cruser_id`, `deleted`, `hidden`,
`starttime`, `endtime`, `fe_group`, `sorting`, `sys_language_uid`, `l10n_parent`,
`l10n_diffsource`, `t3ver_oid`, `t3ver_wsid`, `t3ver_state`, `t3ver_stage`.

| Column | Type | Notes |
| --- | --- | --- |
| `name` | varchar(255) | Record label |
| `street` | varchar(255) | |
| `zip` | varchar(20) | |
| `city` | varchar(255) | |
| `country` | varchar(255) | |
| `phone` | varchar(50) | |
| `email` | varchar(255) | |
| `latitude` | decimal(10,7) | WGS-84 |
| `longitude` | decimal(10,7) | WGS-84 |
| `description` | text | RTE |
| `image` | int(11) | FAL count column (sys_file_reference) |
| `opening_hours` | int(11) | Inline child count column |

Indexes: `PRIMARY KEY (uid)`, `KEY parent (pid)`, `KEY t3ver_oid (t3ver_oid, t3ver_wsid)`,
`KEY language (l10n_parent, sys_language_uid)`.

### `tx_mailocations_opening_hours`

System columns: `uid`, `pid`, `tstamp`, `crdate`, `cruser_id`, `deleted`, `hidden`,
`sorting`, `sys_language_uid`, `l10n_parent`, `l10n_diffsource`,
`t3ver_oid`, `t3ver_wsid`, `t3ver_state`, `t3ver_stage`.

| Column | Type | Notes |
| --- | --- | --- |
| `parentid` | int(11) | FK → `tx_mailocations_location.uid` |
| `parenttable` | varchar(255) | Always `tx_mailocations_location` |
| `day_of_week` | tinyint(4) | 0–6; 'special' stored as 0 when specialDate is set |
| `time_open` | varchar(5) | `HH:MM` |
| `time_close` | varchar(5) | `HH:MM` |
| `is_closed` | tinyint(4) | Boolean flag |
| `note` | varchar(255) | |
| `special_date` | date | NULL for regular days |

Indexes: `PRIMARY KEY (uid)`, `KEY parent (pid)`, `KEY parentid (parentid)`.

---

## 9. Architecture Constraints

- **No SCSS** — styles live in `mai_assets` / `mai_theme`.
- **No mail dispatch** — `mai_mail` is the sole mail owner; `mai_locations` sends no email.
- **No custom category table** — locations do not use `sys_category`; filtering (if needed) is done by storage page.
- **FAL only** — images are stored via `sys_file_reference`; no raw path columns.
- **Map consent** — any external map tile request must be gated via `mai_consent`; the extension provides only the coordinate data attributes, not the map JS.
- **`detailPageUid` required** — the list template links to a separate detail page; both plugins must be placed on separate pages.
- **`mai_base` dependency** — shared icons, TCA helpers, controller base classes, and traits all come from `mai_base`.
