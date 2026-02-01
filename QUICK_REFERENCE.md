# ⚡ QUICK REFERENCE GUIDE

## 🚀 Getting Started

```bash
# Start the server (if not running)
php artisan serve

# Access the app
http://127.0.0.1:8000
```

## 👤 Test Login Credentials

### Admin Account
- **Email**: `admin@example.com`
- **Password**: `password`
- **Access**: Create/manage all events, view admin dashboard

### User Accounts
- **Email**: `user@example.com` / **Password**: `password`
- **Email**: `test@gmail.com` / **Password**: `password`
- **Access**: Register for events, check-in during events, user dashboard

---

## 📍 Key URLs

### Public Pages
| Page | URL |
|------|-----|
| Home | http://127.0.0.1:8000/ |
| Events List | http://127.0.0.1:8000/events |
| Event Detail | http://127.0.0.1:8000/events/1 |
| Login | http://127.0.0.1:8000/login |
| Register | http://127.0.0.1:8000/register |

### Authenticated Pages (User)
| Page | URL |
|------|-----|
| Dashboard | http://127.0.0.1:8000/dashboard |
| Profile | http://127.0.0.1:8000/profile |
| Registrations | http://127.0.0.1:8000/registrations |

### Admin Pages
| Page | URL |
|------|-----|
| Admin Dashboard | http://127.0.0.1:8000/dashboard (as admin) |
| Create Event | http://127.0.0.1:8000/events/create |
| Edit Event | http://127.0.0.1:8000/events/1/edit |
| Check-in Page | http://127.0.0.1:8000/events/1/check-in |

---

## 🔧 Useful Commands

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/EventPoliciesTest.php

# Run tests with coverage
php artisan test --coverage

# Check database migrations status
php artisan migrate:status

# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Refresh database (warning: deletes all data)
php artisan migrate:refresh

# Seed roles
php artisan db:seed --class=RoleSeeder

# Open Tinker console
php artisan tinker

# View routes
php artisan route:list

# Check model cache
php artisan model:cache

# Clear all cache
php artisan cache:clear
```

---

## 🔑 System Features

### ✅ Event Management
- Create events with title, description, location, datetime, capacity
- Status: open, closed, ongoing, completed, cancelled
- Edit and delete events
- View real-time seat availability
- Track current attendees (auto-updating)

### ✅ User Registration
- Users register for events with one-click
- Automatic capacity checking prevents overselling
- Users cannot register twice
- Users can cancel registration
- Real-time availability updates

### ✅ Check-In System
- **Time-Window Based**: Check-in only during event hours
- **Self-Service**: Users check-in during event
- **Admin Override**: Staff can manually check-in
- **Real-Time Stats**: Live attendance tracking
- **Automatic No-Show**: After event ends, unchecked-in users marked as no_show

### ✅ Dashboards
- **User**: View available events, upcoming registrations, past history
- **Admin**: All events by status, capacity stats, live check-in management

---

## 🗄️ Database Schema

### Users
| Column | Type | Notes |
|--------|------|-------|
| id | bigint | Primary key |
| name | string | Legacy |
| full_name | string | New field |
| email | string | Unique |
| phone | string | New field |
| role | enum | 'user', 'admin' |
| email_verified_at | timestamp | Required for access |
| password | string | Hashed |

### Events
| Column | Type | Notes |
|--------|------|-------|
| id | bigint | Primary key |
| user_id | bigint | Event organizer |
| title | string | Event name |
| description | text | Details |
| location | string | Event location |
| start_datetime | datetime | Event start |
| end_datetime | datetime | Event end |
| max_attendees | int | Capacity |
| current_attendees | int | Current count (denormalized) |
| status | enum | 'open', 'closed', 'ongoing', 'completed', 'cancelled' |
| deleted_at | timestamp | Soft delete |

### Registrations
| Column | Type | Notes |
|--------|------|-------|
| id | bigint | Primary key |
| event_id | bigint | Event FK |
| user_id | bigint | User FK |
| status | enum | 'registered', 'checked_in', 'no_show', 'cancelled' |
| registered_at | timestamp | Registration time |
| check_in_time | timestamp | Check-in time (nullable) |
| unique(event_id, user_id) | constraint | Prevents duplicates |

---

## 🔐 Authorization Rules

### Event Creation
- ✅ Admin can create
- ✅ Organizer role can create
- ❌ Regular users cannot

### Event Editing
- ✅ Admin can edit any event
- ✅ Event owner can edit own event
- ❌ Others cannot

### Check-In
- ✅ Admin can perform
- ✅ Event owner can perform
- ✅ Users can self-check-in (time-window validated)
- ❌ Outside event hours: all check-in disabled

### Registration Cancellation
- ✅ User can cancel own registration
- ✅ Organizer can cancel any
- ✅ Admin can cancel any
- ❌ Cannot cancel if event started

---

## 📊 Time-Window Check-In

### How It Works
```
Event created: 2026-02-05 10:00 to 2026-02-05 12:00

09:00 - Check-in disabled (before start)
10:00 - Check-in ENABLED ✅
11:00 - Check-in ENABLED ✅
12:00 - Check-in disabled (after end)
13:00 - Check-in disabled (event ended)
```

### No PIN/QR System
- Trust based on time window
- Database validating attendee
- Optional: Admin can verify photo ID
- No infrastructure overhead

---

## 🐛 Common Issues & Solutions

### Issue: "Email not verified"
**Solution**: Click verification link in email OR access email_verified_at in DB

### Issue: "Cannot create event" (403)
**Solution**: Check user role is 'admin' or has 'organizer' permission

### Issue: "Event is full"
**Solution**: Increase max_attendees OR wait for cancellations

### Issue: "Check-in button disabled"
**Solution**: Wait until event start time (check start_datetime)

### Issue: "Cannot register twice"
**Solution**: User already registered. Cancel first if needed.

---

## 📈 Performance Tips

### Denormalized Fields
- `current_attendees` on Events table
  - Avoids COUNT query on every page load
  - Updated atomically with registrations
  - Trade-off: Extra column vs faster reads

### Database Transactions
- Registration uses `DB::transaction()`
- Prevents race conditions
- Ensures data consistency
- Lock for update on events table

### Indexing
- Foreign keys auto-indexed
- Unique constraint on (event_id, user_id)
- Allows fast user registration queries

---

## 🔍 Debugging

### View Raw SQL
```php
// In Tinker
DB::enableQueryLog();
Event::all();
dd(DB::getQueryLog());
```

### Check Role/Permission
```php
$user = User::first();
$user->role; // Direct field
$user->hasRole('admin'); // Spatie check
$user->hasPermission('create-event'); // Spatie permission
```

### Monitor Registration
```php
$event = Event::find(1);
$event->current_attendees; // Denormalized
$event->registrations->count(); // Actual count
```

---

## 📚 Files to Know

| File | Purpose |
|------|---------|
| `app/Http/Controllers/EventController.php` | Event CRUD |
| `app/Http/Controllers/RegistrationController.php` | Registration & check-in |
| `app/Models/Event.php` | Event model with scopes |
| `app/Policies/EventPolicy.php` | Authorization rules |
| `resources/views/dashboard/admin.blade.php` | Admin dashboard |
| `routes/web.php` | All routes |
| `database/migrations/` | Schema changes |

---

## ✅ Verification Checklist

Before deploying to production:
- [ ] All 34 tests passing
- [ ] No PHP errors in logs
- [ ] No JavaScript console errors
- [ ] Email verification working
- [ ] Capacity enforcement working
- [ ] Time-window check-in working
- [ ] Admin dashboard displaying all events
- [ ] User dashboard showing available events
- [ ] Check-in statistics updating in real-time

---

## 📞 Support

For issues or questions:
1. Check the `TESTING_GUIDE.md` for manual testing steps
2. Check the `FEATURES_CHECKLIST.md` for complete feature list
3. Run `php artisan test` to validate system
4. Check application logs: `storage/logs/laravel.log`

---

**Status**: ✅ Production Ready
**Last Updated**: February 1, 2026

