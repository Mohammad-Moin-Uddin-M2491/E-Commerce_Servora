# Corporate feature implementation note

This note records the corporate account behavior in the current Laravel application. The [B2B guide](B2B_GUIDE.md) contains the user walkthrough and route reference.

## Registration and access

- Corporate registration is available at `/corporate/register`.
- `CorporateRegistrationController` creates the company with `approved` status and assigns the registering customer an active company membership with the `admin` role.
- `AuthPageController` and `PageController` send customers with an active company membership to the corporate dashboard after sign-in or a general dashboard visit.
- Corporate routes require authentication, verification, and corporate membership middleware. Individual actions also check the member's company role.

## Workspace

The corporate navigation links to the dashboard, branches, staff, service requests, and invoices. Routes that need a company ID receive it from the current route or the user's active membership. The dashboard shows company status and summary counts, with actions displayed according to the member's role.

The corporate area also includes booking history and booking details. Staff invitations, branch management, request approval, and invoicing are described in the B2B guide.

## Related files

| Area | Main files |
| --- | --- |
| Registration and redirects | `app/Http/Controllers/CorporateRegistrationController.php`, `AuthPageController.php`, `PageController.php` |
| Access control | `app/Http/Middleware/EnsureCorporateAccess.php` and action-level checks in corporate controllers |
| Navigation and views | `resources/views/components/navbar.blade.php`, `resources/views/corporate/` |
| Routes | `routes/web.php` |
| Data model | `app/Models/Company.php`, membership and related corporate models, `database/migrations/` |

## Verification

For a local check, register a company, sign in as its admin, then visit branches, staff, requests, invoices, and booking history. Repeat with a staff account to check role-specific actions. Run `php artisan test` for the automated suite. These steps describe checks to perform; they are not a claim that every flow has been manually exercised.
