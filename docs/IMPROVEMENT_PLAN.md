# Online Banking — Improvement Plan

This is a **proposed** roadmap; unchecked tasks have not been implemented.

## Security and correctness (highest priority)
- [ ] Validate all transfer amounts as positive, finite values, enforce per-account limits, and include fees when checking available funds.
- [ ] Implement debit, credit and transaction-log writes in one atomic database transaction with rollback on failure.
- [ ] Lock balances or use guarded updates to prevent concurrent overdrafts.
- [ ] Verify sender authorization, recipient existence, account status and currency.
- [ ] Do not accept user role, initial account status or balance from untrusted registration input.
- [ ] Regenerate PHP session IDs on successful login; audit access controls for administrative endpoints.
- [ ] Parameterize *all* SQL statements and validate/types of form input.
- [ ] Keep connection credentials out of committed configuration, and check whether any personal data appears in the spreadsheet.
- [ ] Escape user-controlled output and add CSRF protection to state-changing forms.

## Reproducible development
- [ ] Provide a schema/migration with sanitized example records.
- [ ] Add a documented local PHP/MySQL setup (Docker Compose optional).
- [ ] Add integration tests for registration, login, transfer success/failure and role checks.
- [ ] Capture demo screenshots from sanitized test data only.

## Presentation
- [ ] Decide whether to move the application out of the nested `Online banking/` directory after verifying relative paths.
- [ ] Describe implemented functionality and technical trade-offs without suggesting production readiness.