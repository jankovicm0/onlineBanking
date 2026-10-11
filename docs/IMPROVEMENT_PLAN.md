# Next steps

This is an older university project, so I'm keeping changes small and focused.

## Already improved

- Registration assigns the default user role and account details on the server, rather than accepting them from the form.
- Login regenerates the session ID after a successful password check.
- PHP syntax checks run on GitHub Actions.

## Main issues left

**Transfers:** debit, credit and transaction logging should be part of one database transaction, with rollback on failure and a locking strategy for concurrent requests. Amounts and fees need to be validated together.

**Access control:** verify the authenticated user owns the sender account, validate the recipient, and protect admin actions. Add CSRF protection for forms that change data.

**Database setup:** create a schema/migrations and sanitized seed records so the project can be run without guessing table structures.

**Testing:** cover login, registration, transfers, failed transfers and blocked accounts against a local database.

**Presentation:** add actual screenshots and consider moving the app out of the extra `Online banking/` directory, after checking relative paths.

This is not for real banking or personal data.
