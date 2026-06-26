# Trackit - Issue Tracker

A modern, full-featured issue tracking application built with Laravel and Tailwind CSS. Manage projects, track issues, collaborate with teams, and organize your work efficiently.

## Features

- **User Management**: Register, authenticate, and manage user profiles
- **Team Collaboration**: Create teams, manage team members, and assign roles
- **Projects**: Organize issues into multiple projects
- **Issues**: Create, track, and manage issues with detailed information
- **Comments**: Add discussions and feedback to issues
- **Tags**: Categorize and filter issues with custom tags
- **Role-Based Access Control**: Owner, Member roles with specific permissions
- **Team Member Management**: Invite users, manage roles, and remove members
- **Responsive Design**: Mobile-friendly interface with Bootstrap 5
- **Dark/Light Mode**: Theme switching for user preference

## Tech Stack

- **Backend**: Laravel 13.8, PHP 8.3+
- **Frontend**: Tailwind CSS 4.3, Vite, Bootstrap 5
- **Database**: SQLite (configurable)
- **Testing**: PHPUnit 12.5
- **Code Quality**: Laravel Pint

## Requirements

- PHP 8.3+
- Composer
- Node.js 18+
- npm

## Installation

### Quick Setup

1. Clone the repository:
```bash
git clone https://github.com/rineseee/Trackit.git
cd Trackit
```

2. Run the setup script:
```bash
composer run setup
```

This will:
- Install PHP dependencies
- Create `.env` file from `.env.example`
- Generate application key
- Run database migrations
- Install Node dependencies
- Build frontend assets

### Manual Setup

If you prefer manual setup:

```bash
# Install PHP dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run database migrations
php artisan migrate

# Install Node dependencies
npm install --ignore-scripts

# Build frontend assets
npm run build
```

## Development

Start the development server with all watchers:

```bash
composer run dev
```

This runs:
- Laravel development server
- Queue listener
- Log tail
- Vite dev server

Or run them individually:

```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Queue listener
php artisan queue:listen --tries=1 --timeout=0

# Terminal 3: Logs
php artisan pail --timeout=0

# Terminal 4: Frontend dev server
npm run dev
```

## Building for Production

```bash
npm run build
```

## Testing

Run the test suite:

```bash
composer run test
```

## Database

The application uses SQLite by default. Database file is created at `database/database.sqlite`.

### Running Migrations

```bash
php artisan migrate
```

### Rolling Back Migrations

```bash
php artisan migrate:rollback
```

## Project Structure

```
├── app/
│   ├── Http/
│   │   ├── Controllers/    # Request handlers
│   │   └── Middleware/     # HTTP middleware
│   ├── Models/             # Database models (User, Project, Issue, etc.)
│   └── Policies/           # Authorization policies
├── database/
│   ├── migrations/         # Database schema migrations
│   ├── factories/          # Model factories for testing
│   └── seeders/            # Database seeders
├── resources/
│   ├── views/              # Blade templates
│   ├── css/                # Tailwind CSS
│   └── js/                 # JavaScript files
├── routes/                 # Application routes
└── tests/                  # Test files
```

## Key Models

- **User**: Application users with authentication
- **Team**: Group users together for collaboration
- **Project**: Container for related issues
- **Issue**: Individual tasks or problems to track
- **Comment**: Discussions on issues
- **Tag**: Labels for organizing issues

## API Endpoints

### Authentication
- `POST /login` - User login
- `POST /register` - User registration
- `POST /logout` - User logout

### Projects
- `GET /projects` - List projects
- `POST /projects` - Create project
- `GET /projects/{id}` - View project
- `PUT /projects/{id}` - Update project
- `DELETE /projects/{id}` - Delete project

### Issues
- `GET /issues` - List issues
- `POST /issues` - Create issue
- `GET /issues/{id}` - View issue
- `PUT /issues/{id}` - Update issue
- `DELETE /issues/{id}` - Delete issue

### Teams
- `GET /teams` - List teams
- `POST /teams` - Create team
- `GET /teams/{id}` - View team
- `POST /teams/{id}/members` - Add team member
- `DELETE /teams/{id}/members/{memberId}` - Remove member

## Security

- Password hashing with bcrypt
- CSRF protection on all state-changing requests
- Secure password reset functionality
- Role-based access control (RBAC)
- SQL injection prevention with prepared statements

## Contributing

Contributions are welcome! Please follow these guidelines:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is open-source software licensed under the [MIT license](LICENSE).

## Support

For issues, questions, or suggestions, please:
- Open an issue on GitHub
- Contact: rineskraasniqi@gmail.com

## Author

**Rinesa** - [@rineseee](https://github.com/rineseee)

---

**Trackit** - Making issue tracking simple and effective.
