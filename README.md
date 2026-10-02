# Magedia public demo modules

This repository contains shared infrastructure and optional extension-specific demo adapters. None of these modules belong in customer stores or commercial extension/Core release archives.

- **Magedia_Demo** (repository root): fixed public login (`demo / demo`), warning banner, configurable store link, reset timer, lock-protected reset coordination, and a reset handler contract. It has no PDF Invoice, Abandoned Cart, or sample-data processor dependency. Automatic reset defaults to off.
- **Magedia_DemoSales** (`modules/SalesFixtures`): optional synthetic Magento inventory/order/invoice/credit-memo/shipment fixtures shared by the existing PDF Invoice and Abandoned Cart demos.
- **Magedia_PdfInvoiceDemo** (`modules/PdfInvoiceDemo`): PDF configuration defaults, PDF template restoration, and the PDF demo reset handler.
- **Magedia_AbandonedCartDemo** (`modules/AbandonedCartDemo`): cart-specific reset handler and restoration of the private synthetic rule baseline.

Deploy the root, excluding `modules/`, as `app/code/Magedia/Demo`. Deploy each optional adapter into its corresponding `app/code/Magedia/<module>` directory. PDF Invoice enables Demo, DemoSales, and PdfInvoiceDemo; Abandoned Cart enables Demo, DemoSales, and AbandonedCartDemo. Form Builder enables Demo only and retains its dedicated daily database-baseline reset.

Disable the transitional Magedia_DemoLogin module before enabling common Demo. Run setup upgrade and DI compilation with maintenance enabled and the instance's cron paused/locked; clean configuration/layout caches afterwards. Preserve instance-specific configuration and database backups. Set `magedia_demo/general/marketplace_url` per demo. Form Builder sets `automatic_reset=0` and `banner_disabled=1` because its existing banner describes the external 03:00 UTC reset.

The login plugin substitutes fixed credentials for POST calls to Magento’s backend authentication service, including sign-in from `/admin` before Magento forwards to `auth/login`. Magento's form-key checks, authentication, and restricted role authorization remain in place. Update the public account's password hash and any reset baseline when changing these credentials. The login template uses readonly display fields and hidden submitted fields; server-side handling also tolerates autofill changes to those hidden inputs.

Before enabling the cart reset adapter, capture its synthetic `magedia_acart_rule` rows into `var/demo/abandoned-cart-rules.json`, owned by the Magento filesystem user and mode 0600. Never commit baseline files, visitor data, or credentials. Reset clears only the target extension's custom tables; it never sweeps all Magedia tables or reruns unrelated setup/ACL patches.

Run `php tests/behavior.php` for reset coordination and login request-boundary tests. Validate XML against the installed Magento schemas, compile each enabled module combination, verify a normal login and an intentionally autofill-corrupted login, and check the store's demo links.
