#  WL Wavelength

 WL Wavelength is a simple PHP music streaming and library management app for browsing artists, albums, playlists, and track data.

## Features

- Dashboard overview of the music library
- Browse artists and albums
- View detailed artist and album information
- Manage playlists and track listings
- Add new tracks to the database
- Filter and search records by artist, country, genre, and year

## Tech Stack

- PHP 8+
- MySQL / MariaDB
- HTML,External CSS, and PHP templates
- Laragon local development environment

## Project Structure

- `index.php` - main dashboard
- `artists.php` - artist listing page
- `artist.php` - individual artist details
- `albums.php` - album listing page
- `album.php` - individual album details
- `playlists.php` - playlist overview
- `playlist.php` - single playlist details
- `add_track.php` - add a new track to the library
- `db.php` - database connection settings
- `partials/` - shared header and footer templates
- `css/style.css` - site styling
- `sql/` - schema and import SQL files

## Local Setup

1. Open the project in Laragon or your local PHP web server.
2. Make sure the folder is inside your web root, for example:
   `C:\laragon\www\wavelength`
3. Create or import the MySQL database named `music_streaming`.
4. Update your database credentials in `db.php` if needed.
5. Start the server and open the project in the browser.

Example database config in `db.php`:

```php
$host = "localhost";
$dbname = "music_streaming";
$username = "root";
$password = "12345";
```

## Run the App

Visit the project in your browser, usually at:

```text
http://localhost/wavelength/
```

## Notes

- The app expects the MySQL database tables for `artists`, `albums`, `tracks`, `playlists`, and related data.
- If your database schema differs, update the SQL queries to match your table names and columns.

## Conclusion

 WL Wavelength is a clean PHP-based music library manager that demonstrates database connectivity, SQL querying, filtering, joins, and CRUD operations in a simple web application.