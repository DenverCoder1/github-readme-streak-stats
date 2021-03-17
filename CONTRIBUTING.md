## Contributing

Contributions are welcome! Feel free to open an issue or submit a pull request if you have a way to improve this project.

Make sure your request is meaningful and you have tested the app locally before submitting a pull request.

#### Installing PHP (Linux)

Requirements: [PHP 7.4+](https://www.apachefriends.org/index.html)

```
sudo apt-get install php
sudo apt-get install php-curl
```

[▶ How to install and run PHP using XAMPP (Windows)](https://www.youtube.com/watch?v=K-qXW9ymeYQ)

#### Clone the repository

```
git clone https://github.com/DenverCoder1/github-readme-streak-stats.git
cd github-readme-streak-stats
```

#### Authorization

To get the GitHub API to run locally you will need to provide a token.

1. Go to https://github.com/settings/tokens.
2. Click **"Generate new token."**
3. Add a note (ex. **"Readme Streak Stats"**), then scroll to the bottom and click **"Generate token."**
4. **Copy** the token to your clipboard.
5. **Create** a file `config.php` in the `src` directory and replace `example123` with your **token** and `DenverCoder1` with your **username**:
```php
# /src/config.php
<?php
putenv("TOKEN=example123");
putenv("USERNAME=DenverCoder1");
```

### Running the app locally

```
php -S localhost:8000
```

Open http://localhost:8000/src?user=DenverCoder1 to test the project locally.
