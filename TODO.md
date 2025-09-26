# TODO: Make Site Responsive

## Plan Overview
- Address fixed sidebar in admin.blade.php for mobile.
- Enhance custom.css with more responsive rules.
- Add responsive handling to periodo.css for tables.
- Wrap tables in periodos views with table-responsive.
- Minor tweaks to welcome.blade.php for smaller screens.

## Steps
- [x] Edit resources/views/layouts/admin.blade.php: Make sidebar responsive (hide on small screens).
- [x] Edit resources/css/custom.css: Add media queries for mobile padding, fonts, etc.
- [x] Edit public/css/periodo.css: Add mobile adjustments for tables.
- [x] Edit resources/views/periodos/show.blade.php: Wrap table in table-responsive. (Already wrapped)
- [x] Edit resources/views/periodos/comparativo.blade.php: Wrap table in table-responsive. (Already wrapped)
- [x] Edit resources/views/welcome.blade.php: Add media query for max-width: 480px.
- [x] Test responsiveness after changes. (Added overflow-x: auto to table-responsive for horizontal scroll)
