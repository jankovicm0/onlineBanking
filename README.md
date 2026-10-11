# Online Banking

A PHP and MySQL banking application I made at university. It includes account registration, login, transfers, account management and a few basic reports.

The code is kept as an older coursework project. It isn't suitable for actual banking or handling real customer data.

## Stack

PHP · MySQL/MySQLi · HTML · CSS · JavaScript

## What it does

- Account registration and session-based login
- Transfers between accounts, with a commission
- Account and user management pages
- Transaction views and basic charts

The application lives in the [`Online banking/`](Online%20banking/) directory:

```text
Online banking/
├── controllers/
├── core/
├── models/
├── public/
└── views/
```

## Running it

It was built for a local PHP/MySQL environment, not as a one-command project.

You need PHP with the MySQLi extension, a MySQL server and a compatible database schema. Update the local connection settings in `Online banking/controllers/baza.php` for your machine, then serve the application through your local PHP/Apache setup.

The original repository doesn't contain a SQL schema or migrations, so there's still setup work to make it reproducible from scratch. Please use dummy data only.

## Things I'd improve

Transfers should use database transactions so a failed step can't leave two balances inconsistent. Input validation, authorization, session handling and registration privileges also need more attention.

I've noted the main issues in [the improvement plan](docs/IMPROVEMENT_PLAN.md). This is an academic example, not a production-ready financial system.
