# Jobiz Frontend Documentation

## Design System

### Colors

- Primary: teal-600
- Secondary: slate-700

### Typography

- Font Family: system-ui
- Size Hierarchy:
  - Headings: 1.5rem to 3rem
  - Body: 1rem
  - Small text: 0.875rem

### Components

- Buttons: Rounded with teal-600 background, white text
- Cards: White background with subtle shadow, rounded corners
- Forms: Clean, minimal design with clear labels

## Page Structure & Controllers

### 1. Homepage (HomeController)

**Controller**: `HomeController::index()`

**Design & Content**:

- Hero section with Jobiz logo and tagline
- Short introduction about the platform's purpose
- Featured section displaying the 3 most recent job listings
  - Each job card shows: title, company name, location, and job type
- Call-to-action buttons for "Browse Jobs" and "About Us"

**Route**: `/` (homepage)

**Features**:

- Dynamic loading of latest jobs from database
- Quick access to main website sections

### 2. About Page (PageController)

**Controller**: `PageController::about()`

**Design & Content**:

- Banner image representing workplace/career development
- Mission statement section
- Company history/story section with supporting images
- Team section (optional)
- Contact information

**Route**: `/about`

**Features**:

- Static content with visually appealing layout
- Image gallery integration

### 3. Job Listings Page (JobController)

**Controller**: `JobController::list()`

**Design & Content**:

- Filter sidebar with:
  - Category dropdown/checkboxes
  - Country dropdown
  - Salary range filter (optional)
  - Remote work option filter
- Main content area with job cards:
  - Job title
  - Company name and logo
  - Location (city, country)
  - Job type badge (full-time, part-time, etc.)
  - Short description preview (truncated)
  - Posted date
- Pagination controls (10 jobs per page)

**Route**: `/jobs`

**Query Parameters**:

- `category`: Filter by JobCategory
- `country`: Filter by country
- `page`: Pagination control
- `remote`: Filter by remote option (boolean)

**Features**:

- AJAX filtering (optional enhancement)
- Sorting options (newest first, etc.)
- Responsive grid/list view options

### 4. Job Detail Page (JobController)

**Controller**: `JobController::show()`

**Design & Content**:

- Job header with:
  - Job title
  - Company name and logo
  - Location and remote status
  - Salary range
  - Job type badge
- Full job description section
- Company information section
- Requirements and responsibilities sections
- Application form for logged-in users:
  - Cover letter textarea
  - Submit button
- "Sign in to apply" message for non-authenticated users

**Route**: `/jobs/{id}`

**Features**:

- Authentication check for application form display
- Form validation
- Success confirmation after application submission

### 5. User Authentication Pages (SecurityController)

**Controllers**:

- `SecurityController::login()`
- `SecurityController::register()`
- `SecurityController::profile()`

**Design & Content**:

- Clean login/registration forms
- Profile page showing:
  - User information
  - Application history
  - Application status tracking

**Routes**:

- `/login`
- `/register`
- `/profile`

**Features**:

- Form validation
- Password strength indicators
- Social login options (optional enhancement)

## Shared Components

### Navigation

- Logo in top left
- Main navigation links: Home, Jobs, About
- User menu (Login/Register or profile dropdown when logged in)
- Mobile-responsive hamburger menu

### Footer

- Logo
- Brief description
- Quick links to main pages
- Social media links
- Copyright information

## Responsive Design

All pages will be fully responsive with three main breakpoints:

- Mobile: < 640px
- Tablet: 641px - 1024px
- Desktop: > 1024px

## Implementation Notes

- All pages will use Tailwind CSS for styling
- Utilize Twig templates with layout inheritance (base template with blocks)
- Form handling will use Symfony forms with Tailwind styling
- Flash messages for user feedback after form submissions
