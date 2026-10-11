# Online Banking — Academic PHP Project

> An educational web application that explores everyday banking workflows using PHP and MySQL. **Demo/learning software only — not suitable for real money or personal information.**

## Overview

This university project contains a PHP web application for user accounts, transfers, account administration and simple reports. The repository preserves its original academic structure inside the `Online banking/` directory.

### Features visible in the source

- User registration and sign-in with PHP sessions
- Password hashing and verification during registration/login
- Account balance and transfer workflow with a commission calculation
- User/account management and administrative pages
- Basic reports and charts for users and commissions

These describe code paths in the repository, **not** tested production guarantees.

## Stack

PHP, MySQL (MySQLi), HTML, CSS, JavaScript, and session-based authentication.

## Repository layout

```text
Online banking/
├── controllers/    # Database connection
├── core/           # Transfer and reporting handlers
├── models/         # Page handlers and workflows
├── public/         # CSS, JavaScript, images, basic pages
├── views/          # HTML views
└── Online_banking.xlsx
```

## Running locally

This is a legacy academic project rather than a packaged, one-command application.

1. Install PHP with the `mysqli` extension and a local MySQL server (e.g., a local Apache/PHP stack).
2. Review `Online banking/controllers/baza.php` and supply **your own local database settings**. Never publish credentials.
3. Create a compatible database schema. **A reproducible SQL migration/schema file is not currently supplied**, so the database tables and columns must be reconstructed before the app can run reliably.
4. Serve the `Online banking/` directory through your local PHP/Apache setup and follow the relevant `models/` pages for registration or login.

**Do not expose the current implementation to the public internet.** Local setup is not yet verified from a clean machine.

## Known limitations

- Account-transfer logic requires database transactions, locking, and stronger input validation.
- Some registration-controlled fields (including role/account metadata) should be server-controlled rather than accepted from the client.
- The project needs proper authorization and session-hardening review.
- Tests, database migrations, and a reproducible setup are not yet provided.
- Any example spreadsheet data should be reviewed before external reuse, especially if it contains personal information.

See [IMPROVEMENT_PLAN.md](docs/IMPROVEMENT_PLAN.md) for a prioritized roadmap.

## Project status

**University project / historical snapshot.** Included to demonstrate early full-stack development experience, not as a production banking system.