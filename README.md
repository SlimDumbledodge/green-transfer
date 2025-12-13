# Green Transfer

![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=flat&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=flat&logo=php&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green.svg)

A secure and simple file transfer application built with Laravel. Green Transfer allows users to upload files and share them via unique, time-limited links.

## 🌟 Features

- **Easy File Upload**: Simple drag-and-drop interface for uploading files
- **Secure Sharing**: Each transfer gets a unique UUID-based link
- **Time-Limited Links**: Transfers automatically expire after 7 days
- **Direct Download**: Recipients can download files directly from the browser
- **Automatic Cleanup**: Expired transfers are automatically removed
- **Rate Limiting**: Protected against abuse with request throttling (10 uploads per minute)
- **Responsive Design**: Works seamlessly on desktop and mobile devices

## 📋 Requirements

- PHP 8.1 or higher
- Composer
- MySQL/PostgreSQL/SQLite database
- Node.js and NPM (for frontend assets)

## 🚀 Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/green-transfer.git
   cd green-transfer
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node dependencies**
   ```bash
   npm install
   ```

4. **Configure environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure your database**
   
   Edit the `.env` file with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=green_transfer
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Run migrations**
   ```bash
   php artisan migrate
   ```

7. **Create storage link**
   ```bash
   php artisan storage:link
   ```

8. **Build frontend assets**
   ```bash
   npm run build
   ```

9. **Start the development server**
   ```bash
   php artisan serve
   ```

   The application will be available at `http://localhost:8000`

## 🔧 Configuration

### File Storage

Files are stored in the `storage/app/public/transfers` directory. Make sure this directory is writable:

```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Transfer Expiration

By default, transfers expire after 7 days. You can modify this in the `FileController`:

```php
'expires_at' => now()->addDays(7), // Change the number of days here
```

### Rate Limiting

File uploads are rate-limited to 10 requests per minute. You can adjust this in `routes/web.php`:

```php
Route::post('/file', [FileController::class, 'create'])
    ->middleware('throttle:10,1'); // 10 requests per 1 minute
```

## 📝 Usage

### Uploading a File

1. Navigate to the homepage
2. Select a file or drag and drop it into the upload area
3. Click "Upload"
4. You'll receive a unique shareable link

### Sharing a Transfer

Simply share the generated link with your recipient. They can:
- View file details (name, size, expiration date)
- Download the file directly

### Automatic Cleanup

Set up a cron job to automatically clean expired transfers:

```bash
# Add to your crontab
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

Or manually trigger cleanup:
```bash
php artisan files:cleanup
```

Alternatively, you can visit `/delete-expired-transfers` in your browser.

## 🏗️ Project Structure

```
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── FileController.php    # Main file handling logic
│   └── Models/
│       ├── File.php                  # File model
│       └── Transfer.php              # Transfer model
├── database/
│   └── migrations/                   # Database schema
├── resources/
│   └── views/
│       ├── uploadFile.blade.php      # Upload interface
│       ├── confirmation.blade.php    # Success page
│       └── transfer.blade.php        # Download page
├── routes/
│   └── web.php                       # Application routes
└── storage/
    └── app/
        └── public/
            └── transfers/            # Uploaded files storage
```

## 🔒 Security

- Files are stored with UUID-based paths to prevent unauthorized access
- Transfer links expire automatically after 7 days
- Rate limiting prevents abuse
- File validation ensures only safe uploads
- All routes are protected against common web vulnerabilities

## 🛠️ Development

### Running in Development Mode

```bash
# Terminal 1: Start Laravel server
php artisan serve

# Terminal 2: Watch frontend assets
npm run dev
```

### Running Tests

```bash
php artisan test
```

## 📦 Database Schema

### Transfers Table
- `id`: Primary key
- `uuid`: Unique identifier for the transfer
- `expires_at`: Expiration timestamp
- `status`: Transfer status (active/expired)
- `created_at`, `updated_at`: Timestamps

### Files Table
- `id`: Primary key
- `transfer_id`: Foreign key to transfers
- `original_name`: Original file name
- `stored_path`: Path in storage
- `size`: File size in bytes
- `mime_type`: File MIME type
- `created_at`, `updated_at`: Timestamps

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 👤 Author

Amael

## 🙏 Acknowledgments

- Built with [Laravel](https://laravel.com/)
- Inspired by WeTransfer and similar file transfer services
- Icons and styling powered by modern web standards

---

**Note**: This is a development version. Make sure to properly configure security settings, file size limits, and storage quotas for production use.
