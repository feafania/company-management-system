# Requirements Checklist

## Authentication
- [X] Laravel UI (Bootstrap) used as the default theme
- [X] Administrator can log in
- [X] Registration removed
- [X] Seeder creates admin@admin.com / password

## Companies
- [X] Fields: name (required), email, logo (min 100x100), website
- [X] Models, relationships and factories
- [ ] Logos stored in storage/app/public and accessible from public
- [ ] Full CRUD with resource controller
- [ ] Validation via Request classes
- [ ] Pagination: 10 per page

## Employees
- [X] Fields: first name (required), last name (required), company, email, phone
- [X] Models, relationships and factories
- [ ] Full CRUD with resource controller
- [ ] Validation via Request classes
- [ ] Pagination: 10 per page

## General
- [X] Migrations for both tables
- [ ] Menu items: Companies, Employees
- [ ] Automated tests
- [ ] README with setup instructions
- [ ] Deployed to cPanel
