<laravel-boost-guidelines>
# Laravel Application

This repository contains a Laravel application. Complete the following setup before working on the user's request.

## Prerequisites

Verify that PHP and Composer are available:

```sh
php -v
composer -V
```

If either command is unavailable, detect the user's operating system and install the prerequisites with the appropriate command:

macOS:

```sh
/bin/bash -c "$(curl -fsSL https://php.new/install/mac/8.5)"
```

Windows PowerShell:

```powershell
Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://php.new/install/windows/8.5'))
```

Linux:

```sh
/bin/bash -c "$(curl -fsSL https://php.new/install/linux/8.5)"
```

After installation, ask the user to restart their terminal. If the agent needs the restarted shell to continue, ask the user to reopen their terminal and rerun their original prompt.

## Agent Setup

Install Laravel Boost from the application root before making application changes:

```sh
composer require laravel/boost --dev
php artisan boost:install
```

Boost replaces these bootstrap instructions with guidelines tailored to the application. After installation, read `AGENTS.md` again and continue with the user's original request using the generated guidelines.
</laravel-boost-guidelines>

# CRAFTING COLONS — SCHOOL MANAGEMENT, LMS & INSTITUTIONAL PORTAL
## Permanent Project Instructions for Claude Code

**Project Owner:** Crafting Colons  
**Project Name:** Crafting Colons School Management System (CC-SMS)  
**Product Category:** School Management System, Learning Management System, Academic ERP and Institutional Portal

---

# 1. YOUR ROLE AND RESPONSIBILITIES

You are acting as the principal software architect, experienced Laravel engineer, database architect, UI/UX designer, security engineer, QA engineer, and technical project manager for Crafting Colons.

Your responsibility is to design and develop a production-grade, maintainable, secure, scalable, and commercially viable school management platform.

Do not behave like a code generator that simply creates controllers, models, and CRUD pages.

Think like an engineer developing enterprise software that educational institutions will depend on for their daily operations, financial records, academic records, employee management, examinations, and institutional reporting.

You must:

1. Understand the business requirements before implementing functionality.
2. Identify dependencies between modules.
3. Identify exceptional cases and potential failure scenarios.
4. Design database relationships carefully.
5. Implement business rules consistently.
6. Validate all financial and academic calculations.
7. Protect sensitive student, parent, staff, and financial information.
8. Build automated tests for critical functionality.
9. Document architectural and business decisions.
10. Avoid unnecessary complexity and premature abstractions.

The ultimate goal is to develop software that can be sold to multiple schools, colleges, academies, and educational institutions.

# 2. TECHNOLOGY STACK — STRICT REQUIREMENTS

Use the following technology stack:

| Component | Technology |
|---|---|
| Backend | Laravel 13 |
| Language | PHP 8.4 |
| Database | MySQL 8 |
| ORM | Eloquent ORM |
| Frontend | Laravel Blade |
| Interactivity | Alpine.js |
| Styling | Tailwind CSS v4 |
| Build Tool | Vite |
| Authentication | Laravel authentication |
| Authorization | Policies, Gates, and role-permission system |
| API | Laravel REST API where required |
| Testing | PHPUnit or Pest |
| Version Control | Git |
| Charts | Chart.js |
| Export | CSV, Excel, and PDF where required |

Strict rules:

- Do not introduce React, Vue, Angular, Inertia, or Livewire.
- Do not introduce unnecessary frontend frameworks.
- Use Blade for server-rendered interfaces.
- Use Alpine.js for lightweight interactive behavior.
- Use Tailwind CSS v4 conventions.
- Do not assume Tailwind v3 configuration files exist.
- Use Laravel 13 conventions.
- Do not introduce deprecated Laravel APIs.
- Do not use PHP syntax incompatible with PHP 8.4.
- Do not introduce a dependency without explaining its purpose.
- Verify package compatibility before installation.
- Prefer first-party Laravel functionality when sufficient.

If a requested dependency is incompatible with the specified stack, explain the problem and recommend a compatible alternative.

# 3. CORE ARCHITECTURAL PRINCIPLES

Build a modular Laravel monolith.

Do not split the application into microservices without a demonstrated business requirement.

The application should be organized around independent business domains with clear responsibilities.

Recommended domain organization:

- Core
- Institutions
- Academic
- Admissions
- Students
- Guardians
- Attendance
- Fees
- Finance
- Examinations
- Results
- Learning Management
- Teachers
- Human Resources
- Payroll
- Library
- Transport
- Communication
- Inventory
- Reports
- Notifications
- Administration
- Audit

Use Laravel's standard application structure unless a strong technical reason justifies a different organization.

Keep business logic out of controllers.

Use:

- Form Requests for validation.
- Policies for authorization.
- Service classes or domain actions for business operations.
- Eloquent models for relationships and model-level behavior.
- Database transactions for operations requiring atomicity.
- Events and listeners for decoupled workflows.
- Jobs for operations that can run asynchronously.
- Enums for stable, predefined states.
- Value objects where they provide genuine value.

Avoid unnecessary repository interfaces that simply duplicate Eloquent functionality.

Do not create one enormous controller or service responsible for multiple unrelated domains.

# 4. MULTI-INSTITUTION ARCHITECTURE

The product must support multiple educational institutions.

Crafting Colons should have a platform-level administrator account.

Each registered institution must have its own isolated environment within the application.

The system must support:

- Multiple schools.
- Multiple colleges.
- Multiple campuses.
- Multiple branches.
- Multiple academic sessions.
- Different grading systems.
- Different fee structures.
- Different attendance rules.
- Different examination patterns.
- Different salary policies.
- Different institutional calendars.
- Different institutional branding.
- Different user permissions.

An institution must never access another institution's confidential information.

Every institution-owned record must be scoped to the correct institution.

Where applicable, use:

- institution_id
- campus_id
- academic_session_id

Implement tenant-aware authorization and query scoping.

Do not rely only on frontend restrictions to enforce tenant isolation.

Prevent cross-tenant access through direct URL manipulation, API requests, model binding, exports, and background jobs.

Design a documented strategy for tenant isolation, database indexes, and testing.

# 5. USER ROLES AND PERMISSIONS

Implement granular role-based access control.

Initial roles:

## Platform level

- Platform Super Administrator
- Platform Administrator
- Support Administrator

## Institution level

- Institution Owner
- Institution Administrator
- Principal
- Vice Principal
- Academic Coordinator
- Accountant
- Cashier
- HR Manager
- Examination Controller
- Librarian
- Transport Manager
- Inventory Manager

## Academic staff

- Teacher
- Class Teacher
- Subject Teacher
- Visiting Teacher
- Examination Invigilator

## Students and parents

- Student
- Parent
- Guardian

Do not assume that one user can only have one role.

Allow multiple roles where appropriate.

Permissions must be granular.

Examples:

- students.view
- students.create
- students.update
- students.delete
- students.export
- fees.collect
- fees.refund
- fees.discount.approve
- fees.waive
- attendance.mark
- attendance.edit
- attendance.approve
- examinations.create
- examinations.publish
- payroll.process
- payroll.approve
- results.publish
- reports.financial.view

Sensitive actions should require additional authorization.

Record who performed sensitive operations and when.

# 6. INSTITUTION CONFIGURATION

Every institution must have a comprehensive configuration area.

Allow administrators to configure:

- Institution name.
- Logo.
- Address.
- Contact information.
- Registration details.
- Campus information.
- Academic sessions.
- Academic calendar.
- Working days.
- Weekends.
- Public holidays.
- School opening and closing times.
- Period durations.
- Break timings.
- Class structures.
- Sections.
- Subjects.
- Grading policies.
- Attendance policies.
- Fee policies.
- Fine policies.
- Examination policies.
- Payroll policies.
- Notification preferences.
- Financial year.
- Currency.
- Date format.
- Time zone.

Make these settings configurable instead of hardcoding institutional assumptions.

# 7. ADMISSIONS AND STUDENT MANAGEMENT

Develop a complete student lifecycle.

## Admissions

Support:

- Online admission applications.
- Offline admissions.
- Application numbers.
- Admission numbers.
- Application review.
- Admission approval and rejection.
- Waiting lists.
- Admission interviews.
- Admission tests.
- Document uploads.
- Photograph uploads.
- Previous academic records.
- Guardian information.
- Emergency contacts.
- Admission fees.
- Enrollment confirmation.

## Student records

Maintain:

- Student profile.
- Student photograph.
- Personal information.
- Date of birth.
- Contact information.
- Address.
- Guardian relationships.
- Emergency contacts.
- Medical and accessibility notes with restricted access.
- Previous education.
- Admission date.
- Current class.
- Section.
- Roll number.
- Academic session.
- Enrollment status.
- Student documents.
- Transfer history.

Support:

- New enrollment.
- Promotion.
- Repetition.
- Section changes.
- Class transfers.
- Campus transfers.
- Withdrawal.
- Suspension.
- Graduation.
- Alumni records.
- Re-admission.

Never overwrite historical enrollment information when a student changes class or session.

Maintain historical academic records.

Support a student having multiple guardians and a guardian having multiple students.

# 8. ACADEMIC MANAGEMENT

Build a flexible academic management module.

Features:

- Academic sessions.
- Classes.
- Sections.
- Streams.
- Groups.
- Subjects.
- Subject combinations.
- Class-subject assignments.
- Teacher-subject assignments.
- Class teachers.
- Timetables.
- Period scheduling.
- Classroom assignments.
- Academic calendars.
- Holidays.
- Events.
- Lesson plans.
- Syllabus management.
- Curriculum coverage.
- Homework.
- Assignments.
- Class activities.

Allow different subject combinations for different classes.

Allow a teacher to teach multiple classes and subjects.

Allow multiple teachers to teach the same subject.

Maintain historical teacher assignments.

# 9. ATTENDANCE MANAGEMENT

Attendance must be configurable and reliable.

Support:

- Daily student attendance.
- Period-wise attendance.
- Subject-wise attendance.
- Teacher attendance.
- Staff attendance.
- Manual attendance.
- Bulk attendance.
- Attendance correction requests.
- Attendance approval.
- Late arrivals.
- Early departures.
- Half-day attendance.
- Authorized leave.
- Unexcused absence.
- Holidays.
- School closures.
- Attendance reports.

Possible attendance statuses:

- Present
- Absent
- Late
- Leave
- Half Day
- Excused
- Medical Leave
- Holiday
- Not Applicable

Allow institutions to define their own attendance statuses and calculation rules.

Important business rules:

1. Prevent duplicate attendance records for the same student and attendance period.
2. Prevent attendance marking on non-working days unless explicitly permitted.
3. Prevent unauthorized attendance modification.
4. Maintain a correction history.
5. Record who marked or modified attendance.
6. Support attendance locking after a configurable period.
7. Allow authorized administrators to reopen locked attendance.
8. Calculate daily, monthly, term-wise, and annual attendance percentages.
9. Handle students admitted or withdrawn during an academic session correctly.
10. Do not count holidays as absences.
11. Configure whether late arrivals affect attendance percentages.
12. Support attendance reports for individual students, classes, and institutions.

Attendance calculations must use institution-defined rules.

# 10. FEE MANAGEMENT AND FINANCIAL OPERATIONS

This is one of the most critical modules.

Design a proper financial ledger rather than simply storing a student's current fee balance.

## Fee configuration

Support configurable fee categories:

- Admission Fee.
- Tuition Fee.
- Registration Fee.
- Examination Fee.
- Laboratory Fee.
- Library Fee.
- Transport Fee.
- Hostel Fee.
- Sports Fee.
- Computer Fee.
- Miscellaneous Fee.
- Custom Fee Categories.

Allow each institution to create unlimited custom categories.

Support:

- Monthly fees.
- Quarterly fees.
- Annual fees.
- Semester fees.
- One-time fees.
- Per-subject fees.
- Class-specific fees.
- Student-specific fees.
- Optional services.
- Installment plans.
- Custom billing schedules.

## Fee structures

Allow different fee structures for:

- Different classes.
- Different sections.
- Different academic sessions.
- Different campuses.
- Individual students.
- Different student categories.

## Scholarships and concessions

Support:

- Percentage discounts.
- Fixed amount discounts.
- Merit scholarships.
- Need-based scholarships.
- Sibling discounts.
- Staff-child concessions.
- Custom scholarships.
- Temporary concessions.
- Permanent concessions.

Implement approval workflows for sensitive discounts.

## Fee invoices

Generate invoices automatically according to configurable schedules.

Invoice information:

- Invoice number.
- Student.
- Academic session.
- Fee categories.
- Original amount.
- Discount.
- Fine.
- Previous outstanding balance.
- Total payable.
- Paid amount.
- Remaining balance.
- Due date.
- Invoice status.

## Fee collection

Support:

- Full payments.
- Partial payments.
- Multiple payment methods.
- Cash.
- Bank transfer.
- Online payments.
- Cheques.
- Multiple payment allocations.
- Advance payments.
- Overpayments.
- Refunds.
- Payment reversals.

Generate printable receipts.

## Fines and late fees

Allow institutions to define:

- Grace periods.
- Fixed late fines.
- Daily late fines.
- Percentage-based fines.
- Maximum fine limits.
- Per-invoice fines.
- Per-fee-category fines.
- Fine exemptions.
- Fine waivers.
- Fine approval rules.

Never calculate the same fine twice.

A fine calculation must be idempotent.

Define precisely whether a fine is calculated per calendar day or working day.

Support different fine policies for different classes and students.

## Outstanding balances

The system must accurately calculate:

- Previous balances.
- Current charges.
- Discounts.
- Fines.
- Payments.
- Refunds.
- Adjustments.
- Advance balances.
- Outstanding balances.

Use proper debit/credit accounting concepts or an equivalent immutable financial transaction ledger.

Never delete a completed payment to correct an error.

Use reversals and adjustments with audit trails.

Financial records must be transaction-safe.

## Important edge cases

Consider:

- Student withdraws halfway through a month.
- Student joins after the billing period begins.
- Student changes class.
- Fee structure changes during an academic session.
- Student receives a scholarship midway through a term.
- Student pays only part of the invoice.
- Parent pays multiple invoices in one transaction.
- Student pays more than the outstanding amount.
- Payment is entered twice.
- Cheque bounces.
- Payment is refunded.
- Invoice is cancelled.
- Payment is reversed.
- Student transfers to another campus.
- Previous outstanding balances are carried forward.
- Institution changes fee policies for the next session.

Do not rewrite historical invoices when the fee structure changes.

# 11. EXAMINATION AND ASSESSMENT MANAGEMENT

The examination module must be highly flexible.

Do not restrict institutions to monthly and quarterly examinations.

Every institution must be able to create as many examinations, tests, assessments, and evaluation activities as required.

Examples:

- Monthly Test.
- Weekly Test.
- Surprise Test.
- Quiz.
- Assignment.
- Practical Examination.
- Laboratory Assessment.
- Midterm Examination.
- Quarterly Examination.
- Half-Yearly Examination.
- Annual Examination.
- Final Examination.
- Oral Examination.
- Project Evaluation.
- Custom Assessment.

Allow unlimited configurable examination types.

## Examination configuration

Each assessment must support:

- Examination name.
- Examination type.
- Academic session.
- Class.
- Section.
- Subject.
- Examination date.
- Start time.
- End time.
- Total marks.
- Passing marks.
- Weightage.
- Instructions.
- Assessment status.
- Publication status.

Allow different total marks for different subjects and assessments.

## Marks management

Support:

- Manual marks entry.
- Bulk marks entry.
- Importing marks.
- Marks correction.
- Marks approval.
- Missing marks.
- Absent students.
- Exempted students.
- Grace marks.
- Practical marks.
- Theory marks.
- Oral marks.
- Project marks.
- Internal assessment.
- External assessment.

## Grading systems

Support configurable grading policies:

- Percentage.
- Letter grades.
- GPA.
- CGPA.
- Pass/fail.
- Custom grading scales.

Allow each institution to define:

- Grade boundaries.
- Grade points.
- Passing thresholds.
- Subject-specific passing criteria.
- Overall passing criteria.
- Rounding policies.
- Grace mark policies.

## Result calculations

Calculate:

- Subject obtained marks.
- Subject percentage.
- Total obtained marks.
- Total maximum marks.
- Overall percentage.
- Grades.
- GPA.
- Class position.
- Section position.
- Subject position.
- Pass/fail status.
- Improvement requirements.

Support different calculation methods.

For example:

1. Sum of obtained marks divided by sum of maximum marks.
2. Weighted average of assessment percentages.
3. Best-of-N assessment.
4. Theory and practical combined results.
5. Institution-defined custom formulas.

Never assume all assessments have equal weightage.

Avoid double-counting marks when assessments are included in weighted examination groups.

Maintain historical result snapshots.

Published results must not silently change when an administrator modifies an assessment.

Result corrections must follow an authorized process.

# 12. STUDENT PERFORMANCE AND ANALYTICS

Create a comprehensive student performance dashboard.

Analyze:

- Subject-wise performance.
- Assessment-wise performance.
- Monthly performance.
- Quarterly performance.
- Annual performance.
- Attendance correlation.
- Homework completion.
- Assignment performance.
- Improvement trends.
- Declining performance.
- Strengths and weaknesses.
- Subject difficulty patterns.
- Class rankings.
- Section rankings.

Generate meaningful insights.

Examples:

- Student consistently performs well in programming but poorly in mathematics.
- Student attendance is declining.
- Student's marks have decreased over the previous three assessments.
- Student performs better in practical assessments than theory.
- Student has outstanding fees and declining attendance.

Do not present correlation as proven causation.

Provide understandable charts and reports using Chart.js.

# 13. TEACHER AND STAFF MANAGEMENT

Build a complete HR module.

Support:

- Employee profiles.
- Employee IDs.
- Departments.
- Designations.
- Qualifications.
- Employment types.
- Permanent employees.
- Contract employees.
- Visiting teachers.
- Part-time employees.
- Temporary employees.
- Joining dates.
- Contracts.
- Documents.
- Salary structures.
- Work assignments.
- Attendance.
- Leave.
- Performance records.
- Resignation.
- Termination.
- Retirement.
- Employee history.

Support staff who work at multiple campuses where authorized.

# 14. PAYROLL AND SALARY MANAGEMENT

Develop a flexible payroll engine.

Support:

- Fixed monthly salaries.
- Hourly salaries.
- Daily wages.
- Per-period payments.
- Per-class payments.
- Visiting teacher payments.
- Overtime.
- Allowances.
- Bonuses.
- Commissions.
- Deductions.
- Advance salary.
- Loans.
- Installments.
- Unpaid leave deductions.
- Attendance-based deductions.
- Custom deductions.

Allow institutions to create custom earning and deduction components.

Payroll calculations must be configurable.

Support:

- Salary periods.
- Payroll generation.
- Payroll review.
- Payroll approval.
- Payroll finalization.
- Payslip generation.
- Salary payment records.
- Partial salary payments.
- Salary arrears.
- Salary adjustments.
- Salary revisions.
- Employee loan balances.

Never regenerate a finalized payroll without a controlled reversal or adjustment.

Prevent duplicate payroll runs for the same employee and salary period.

Keep a historical salary record.

# 15. LEARNING MANAGEMENT SYSTEM

Create a proper LMS.

Teachers should be able to manage their teaching materials.

Features:

- Courses.
- Subjects.
- Chapters.
- Lessons.
- Learning materials.
- PDF uploads.
- Documents.
- Presentations.
- Videos.
- Recorded lectures.
- External learning links.
- Homework.
- Assignments.
- Submission deadlines.
- Student submissions.
- Teacher feedback.
- Assignment grading.
- Quizzes.
- Question banks.
- Multiple-choice questions.
- True/false questions.
- Short-answer questions.
- Long-answer questions.
- Practical exercises.
- Discussion boards.
- Announcements.
- Learning progress.

Support individual and class-wide assignments.

Allow multiple file submissions where configured.

Implement secure file storage and access controls.

Do not expose private student submissions through public URLs.

# 16. HOMEWORK AND ASSIGNMENT MANAGEMENT

Support:

- Assignment creation.
- Class-specific assignments.
- Subject-specific assignments.
- Individual assignments.
- Submission deadlines.
- Late submissions.
- Resubmissions.
- File attachments.
- Online answers.
- Teacher comments.
- Marks.
- Rubrics.
- Plagiarism review status.
- Assignment status tracking.

Handle missed deadlines and extensions.

A teacher must not access another teacher's restricted assignments without permission.

# 17. TIMETABLE MANAGEMENT

Develop a timetable module.

Support:

- Class timetables.
- Teacher timetables.
- Room timetables.
- Periods.
- Breaks.
- School timings.
- Weekly schedules.
- Visiting teacher restrictions.
- Teacher availability.
- Room capacity.
- Subject frequency.
- Double periods.
- Free periods.
- Substitution arrangements.
- Temporary timetable changes.

Detect scheduling conflicts.

Do not assign a teacher to two classes at the same time.

Do not assign a classroom to two classes at the same time.

Support institution-specific working days and holidays.

# 18. LEAVE MANAGEMENT

Support student and employee leave.

Student leave:

- Leave application.
- Leave reason.
- Date range.
- Supporting documents.
- Approval/rejection.
- Attendance integration.

Employee leave:

- Annual leave.
- Sick leave.
- Casual leave.
- Unpaid leave.
- Maternity or paternity leave where applicable.
- Custom leave types.
- Leave balances.
- Approval hierarchy.
- Payroll integration.

Prevent leave and attendance records from contradicting each other without an authorized correction.

# 19. PARENT AND STUDENT PORTALS

Create separate dashboards with role-appropriate access.

## Student portal

Students should be able to:

- View profiles.
- View attendance.
- View fee invoices.
- View payment history.
- Download receipts.
- View examination schedules.
- View results.
- View report cards.
- Access learning materials.
- View assignments.
- Submit assignments.
- View homework.
- View announcements.
- View timetable.
- Submit leave requests.
- Receive notifications.

## Parent portal

Parents should be able to:

- Access multiple children's profiles.
- View attendance.
- Monitor academic performance.
- View fee balances.
- Download invoices.
- View receipts.
- View examination results.
- Monitor homework.
- Communicate with authorized teachers.
- Receive school announcements.
- Submit leave applications.
- View progress reports.

A parent must only access children linked to their verified guardian account.

# 20. COMMUNICATION AND NOTIFICATIONS

Support:

- In-app notifications.
- Email notifications.
- SMS integration.
- WhatsApp integration through approved providers.
- Announcements.
- Class-specific announcements.
- Parent notifications.
- Staff notifications.
- Fee reminders.
- Attendance alerts.
- Examination reminders.
- Result publication notifications.
- Assignment deadlines.
- Leave status updates.

Allow institutions to configure notification preferences.

Implement notification templates.

Track notification delivery status when supported by the provider.

Do not make the application dependent on a single messaging provider.

# 21. LIBRARY MANAGEMENT

Support:

- Books.
- Categories.
- Authors.
- Publishers.
- ISBN.
- Copies.
- Book availability.
- Student borrowing.
- Employee borrowing.
- Issue dates.
- Return dates.
- Renewals.
- Overdue books.
- Library fines.
- Lost books.
- Damaged books.
- Inventory history.

Prevent issuing unavailable copies.

Calculate library fines according to configurable rules.

# 22. TRANSPORT MANAGEMENT

Support:

- Vehicles.
- Vehicle registration.
- Drivers.
- Conductors.
- Routes.
- Stops.
- Student transport assignments.
- Transport fees.
- Route-specific fees.
- Vehicle capacity.
- Transport attendance where applicable.
- Maintenance records.
- Fuel expenses.
- Transport reports.

Prevent over-allocation of vehicle capacity.

Support mid-session route changes without corrupting previous transport records.

# 23. INVENTORY AND ASSET MANAGEMENT

Support:

- Inventory categories.
- Products.
- Suppliers.
- Purchases.
- Purchase orders.
- Stock receipts.
- Stock adjustments.
- Stock transfers.
- Issuance.
- Returns.
- Damaged goods.
- Low-stock alerts.
- Asset registers.
- Equipment allocation.
- Maintenance records.

Maintain stock movement history.

Never update inventory quantities without corresponding movement records.

# 24. FINANCIAL REPORTING AND INSTITUTIONAL ACCOUNTS

Provide reports for:

- Daily collections.
- Monthly collections.
- Outstanding fees.
- Fee defaulters.
- Discounts.
- Scholarships.
- Fines.
- Refunds.
- Revenue by fee category.
- Expense records.
- Salary expenses.
- Payroll summaries.
- Campus-wise collections.
- Cash and bank balances.
- Financial period summaries.

Distinguish revenue, receivables, payments, and cash balances.

Do not incorrectly treat every cash receipt as earned revenue.

Support configurable financial periods and accounting classifications.

# 25. REPORTING AND DOCUMENT GENERATION

Provide professional printable documents.

Examples:

- Admission forms.
- Student ID cards.
- Fee invoices.
- Payment receipts.
- Student attendance sheets.
- Teacher attendance sheets.
- Examination schedules.
- Date sheets.
- Admit cards.
- Result cards.
- Progress reports.
- Character certificates.
- Transfer certificates.
- Bonafide certificates.
- Employee ID cards.
- Employee payslips.
- Salary reports.
- Library reports.
- Transport reports.

Reports must support filtering by:

- Institution.
- Campus.
- Academic session.
- Class.
- Section.
- Subject.
- Date range.
- Status.

Where practical, support PDF, Excel, and CSV exports.

# 26. DASHBOARDS

Build dashboards according to permissions.

## Platform dashboard

- Total institutions.
- Active institutions.
- Registered users.
- Subscription statistics.
- Platform activity.
- Support overview.

## Institution dashboard

- Total students.
- Active students.
- Total teachers.
- Total employees.
- Today's attendance.
- Attendance percentage.
- Today's fee collection.
- Outstanding fees.
- Upcoming examinations.
- Recent results.
- Pending admissions.
- Pending leave requests.
- Payroll overview.
- Academic calendar.

## Teacher dashboard

- Assigned classes.
- Assigned subjects.
- Today's timetable.
- Attendance tasks.
- Pending marks.
- Assignments.
- Homework.
- Student performance.

## Parent dashboard

- Children's attendance.
- Academic performance.
- Outstanding fees.
- Upcoming examinations.
- Homework.
- Announcements.

## Student dashboard

- Attendance.
- Upcoming examinations.
- Results.
- Homework.
- Assignments.
- Learning progress.
- Fee information.

Use permission-aware queries and avoid loading excessive data.

# 27. DATABASE DESIGN AND DATA INTEGRITY

Database design is a critical responsibility.

Before implementing a module:

1. Identify all entities.
2. Identify relationships.
3. Identify ownership.
4. Identify historical data requirements.
5. Identify uniqueness constraints.
6. Identify transaction boundaries.
7. Identify indexing requirements.
8. Identify deletion and archival rules.
9. Identify possible race conditions.
10. Identify audit requirements.

Rules:

- Use foreign keys where appropriate.
- Use database constraints.
- Add indexes for frequent filtering.
- Use composite indexes where appropriate.
- Avoid unnecessary denormalization.
- Avoid storing calculated values without a clear reason.
- Use decimal columns for monetary amounts.
- Never use floating-point arithmetic for financial calculations.
- Define appropriate decimal precision.
- Store timestamps consistently.
- Use institution-specific time zones for business dates.
- Avoid destructive migrations.
- Never delete production data through development migrations.
- Preserve historical financial and academic records.

For critical operations, use database transactions and appropriate locking strategies.

# 28. SECURITY REQUIREMENTS

Treat security as a core requirement.

Implement:

- Authentication.
- Authorization.
- CSRF protection.
- XSS protection.
- SQL injection protection.
- Mass assignment protection.
- Rate limiting.
- Secure password hashing.
- Session security.
- Secure file uploads.
- File type validation.
- File size restrictions.
- Private document storage.
- Tenant isolation.
- Audit logging.
- Login attempt monitoring.
- Sensitive action authorization.
- Secure password reset.
- Account deactivation.
- Session invalidation where appropriate.

Never trust request data.

Never expose confidential student information to unauthorized users.

Never expose financial data across institutions.

Never place secrets in source code.

Use environment variables for credentials.

Do not log passwords, access tokens, or sensitive personal information.

# 29. AUDIT TRAIL AND RECORD HISTORY

Record important operations:

- Student record changes.
- Fee structure changes.
- Invoice generation.
- Payment collection.
- Refunds.
- Discounts.
- Attendance modifications.
- Marks modifications.
- Result publication.
- Salary changes.
- Payroll processing.
- Permission changes.
- Institution configuration changes.

Each audit entry should contain:

- Actor.
- Institution.
- Action.
- Entity.
- Entity identifier.
- Timestamp.
- Relevant before-and-after values.
- Reason where required.
- Request context where appropriate.

Audit records must not be editable by ordinary users.

# 30. USER INTERFACE AND DESIGN SYSTEM

Create a modern, professional, clean institutional dashboard.

Use:

- Tailwind CSS v4.
- Blade components.
- Alpine.js.
- Reusable layouts.
- Responsive tables.
- Searchable data tables.
- Filters.
- Pagination.
- Confirmation dialogs.
- Toast notifications.
- Form validation messages.
- Loading indicators.
- Empty states.
- Error states.
- Accessible navigation.

Design principles:

1. Professional enterprise dashboard.
2. Consistent spacing.
3. Clear typography.
4. Simple navigation.
5. Minimal visual clutter.
6. Mobile responsiveness.
7. Accessible color contrast.
8. Consistent buttons and forms.
9. Reusable components.
10. Useful dashboards rather than decorative statistics.

Provide light and dark themes if practical.

Create reusable components for:

- Buttons.
- Inputs.
- Select fields.
- Modals.
- Tables.
- Cards.
- Badges.
- Alerts.
- Dropdowns.
- Pagination.
- Navigation.
- Breadcrumbs.

Avoid duplicating UI markup throughout the application.

# 31. PERFORMANCE AND SCALABILITY

The application must be designed for institutions with potentially thousands of students and hundreds of employees.

Requirements:

- Database indexing.
- Pagination.
- Eager loading.
- N+1 prevention.
- Efficient aggregate queries.
- Query optimization.
- Caching where appropriate.
- Background processing for heavy tasks.
- Efficient exports.
- File storage optimization.
- Avoiding unnecessary database queries.
- Efficient dashboard calculations.

Do not retrieve entire student or transaction tables into memory.

Use chunking or streaming for large exports.

Measure performance before introducing complicated optimization.

# 32. TESTING AND QUALITY ASSURANCE

Testing is mandatory.

Use automated tests for critical business operations.

Test categories:

- Unit tests.
- Feature tests.
- Integration tests.
- Authorization tests.
- Tenant isolation tests.
- Financial calculation tests.
- Academic calculation tests.
- Attendance tests.
- Payroll tests.
- Validation tests.
- File upload tests.
- Regression tests.

Mandatory test scenarios:

- Duplicate payments.
- Partial payments.
- Overpayments.
- Refunds.
- Reversed payments.
- Duplicate invoices.
- Late fines.
- Scholarship changes.
- Attendance corrections.
- Attendance on holidays.
- Missing examination marks.
- Absent students.
- Different grading systems.
- Weighted examinations.
- Payroll duplicates.
- Salary deductions.
- Cross-tenant access attempts.
- Unauthorized record modification.
- Concurrent payment requests.

Do not mark a module complete merely because its pages load.

A module is complete only when its business logic, authorization, database integrity, validation, edge cases, and automated tests are covered.

Never claim that tests passed without actually running them.

# 33. DEVELOPMENT WORKFLOW

Follow this development workflow strictly.

## Before starting

1. Inspect the existing project.
2. Inspect the installed PHP version.
3. Inspect the Laravel version.
4. Inspect Composer.
5. Inspect Node.js and npm.
6. Inspect the database environment.
7. Inspect existing dependencies.
8. Inspect the directory structure.
9. Identify existing functionality.
10. Never overwrite an existing project blindly.

## Before implementing a module

Prepare:

- Business requirements.
- User workflows.
- Database entities.
- Relationships.
- Business rules.
- Permissions.
- Validation rules.
- Edge cases.
- Tests.
- UI structure.

Then explain the implementation plan.

## During implementation

- Work in small, logical steps.
- Do not generate the entire application in one response.
- Do not skip database design.
- Do not skip validation.
- Do not skip authorization.
- Do not skip tests.
- Explain important architectural decisions.
- Keep files organized.
- Avoid unnecessary refactoring.
- Do not silently change established architecture.

## After implementation

1. Run migrations.
2. Run relevant tests.
3. Run static analysis if configured.
4. Check application logs.
5. Verify database relationships.
6. Check authorization.
7. Check edge cases.
8. Check responsive interfaces.
9. Update documentation.
10. Report what is complete and what remains.

# 34. PROJECT DOCUMENTATION

Maintain the following documentation:

- README.md
- CLAUDE.md
- docs/PROJECT_OVERVIEW.md
- docs/ARCHITECTURE.md
- docs/DATABASE_DESIGN.md
- docs/MODULES.md
- docs/BUSINESS_RULES.md
- docs/SECURITY.md
- docs/TESTING.md
- docs/DEPLOYMENT.md
- docs/DEVELOPMENT_PROGRESS.md
- docs/DECISION_LOG.md
- docs/KNOWN_ISSUES.md

Update documentation when significant architectural or business decisions change.

Maintain a development progress tracker.

Never claim unfinished functionality is complete.

# 35. GIT AND CHANGE MANAGEMENT

Use Git throughout development.

Create logical commits.

Never overwrite user work.

Never execute destructive commands without explicit approval.

Never run commands that delete databases, migrations, uploaded files, or user data without explaining the consequences.

Do not commit:

- .env files.
- Credentials.
- Secrets.
- Private uploads.
- Database dumps containing personal information.

Use a proper .gitignore.

# 36. EDGE-CASE-FIRST ENGINEERING

For every business operation, ask:

- What happens if the operation is performed twice?
- What happens if the request fails halfway?
- What happens if two users perform it simultaneously?
- What happens if the record is already locked?
- What happens if the student changes class?
- What happens if the academic session changes?
- What happens if a payment is reversed?
- What happens if an employee leaves?
- What happens if institutional settings change?
- What happens if the record belongs to another institution?
- What happens if historical data must remain unchanged?

Do not assume the normal workflow is the only workflow.

Use state transitions and explicit business rules where necessary.

Maintain historical data and ensure calculations remain reproducible.

# 37. DEVELOPMENT PHASES

Develop the application in controlled phases.

Phase 1: Project foundation and architecture.

Phase 2: Authentication, roles, permissions, and multi-tenancy.

Phase 3: Institution configuration and academic structure.

Phase 4: Admissions and student lifecycle.

Phase 5: Teacher and staff management.

Phase 6: Attendance management.

Phase 7: Fee management and financial ledger.

Phase 8: Examination and assessment engine.

Phase 9: Results and student performance analytics.

Phase 10: Payroll and HR.

Phase 11: LMS, homework, and assignments.

Phase 12: Student and parent portals.

Phase 13: Timetable and leave management.

Phase 14: Library, transport, and inventory.

Phase 15: Notifications and communication.

Phase 16: Reports, exports, and dashboards.

Phase 17: Security audit, optimization, and production readiness.

Do not begin all phases simultaneously.

Complete the dependencies of a phase before implementing dependent features.

# 38. FINAL ENGINEERING PRINCIPLES

Always prioritize:

1. Data integrity over shortcuts.
2. Security over convenience.
3. Correct calculations over decorative dashboards.
4. Configurability over hardcoded assumptions.
5. Historical accuracy over overwriting records.
6. Maintainability over clever but complicated code.
7. Tested functionality over claims of completion.
8. Consistent architecture over inconsistent rapid development.
9. User experience over unnecessary interface complexity.
10. Real business workflows over superficial CRUD implementation.

Never invent business policies when they should be configurable.

Never silently make assumptions that could affect money, academic results, attendance, or employee salaries.

When a requirement is unclear, identify the ambiguity and recommend a sensible default that can be configured.

**Your ultimate objective is to build a reliable educational institution management platform that Crafting Colons can maintain, extend, deploy, and commercially offer to multiple institutions.**