# Requirements Checklist

## Authentication
- [ ] Laravel UI (Bootstrap) used as the default theme
- [ ] Administrator can log in
- [ ] Registration removed
- [ ] Seeder creates admin@admin.com / password

## Companies
- [ ] Fields: name (required), email, logo (min 100x100), website
- [ ] Logos stored in storage/app/public and accessible from public
- [ ] Full CRUD with resource controller
- [ ] Validation via Request classes
- [ ] Pagination: 10 per page

## Employees
- [ ] Fields: first name (required), last name (required), company, email, phone
- [ ] Full CRUD with resource controller
- [ ] Validation via Request classes
- [ ] Pagination: 10 per page

## General
- [ ] Migrations for both tables
- [ ] Menu items: Companies, Employees
- [ ] Automated tests
- [ ] README with setup instructions
- [ ] Deployed to cPanel