## Contributing

Contributions are welcome! Feel free to open an issue or submit a pull request if you have a way to improve this project.

Make sure your request is meaningful and you have tested the app locally before submitting a pull request.


### Installing Requirements

#### Requirements

* [PHP 7.4+](https://www.apachefriends.org/index.html)
* [Composer](https://getcomposer.org)

#### Linux

```bash
sudo apt-get install php
sudo apt-get install php-curl
sudo apt-get install composer
```

#### Windows

Install PHP from [XAMPP](https://www.apachefriends.org/index.html) or [php.net](https://windows.php.net/download)

[▶ How to install and run PHP using XAMPP (Windows)](https://www.youtube.com/watch?v=K-qXW9ymeYQ)

[📥 Download Composer](https://getcomposer.org/download/)

### Clone the repository

```
git clone https://github.com/DenverCoder1/github-readme-streak-stats.git
cd github-readme-streak-stats
```

### Authorization

To get the GitHub API to run locally you will need to provide a token.

1. Go to https://github.com/settings/tokens.
2. Click **"Generate new token."**
3. Add a note (ex. **"GitHub Readme Streak Stats"**), then scroll to the bottom and click **"Generate token."**
4. **Copy** the token to your clipboard.
5. **Create** a file `config.php` in the `src` directory and replace `ghp_example123` with **your token** and `DenverCoder1` with **your username**:

```php
<?php
putenv("TOKEN=ghp_example123");
putenv("USERNAME=DenverCoder1");
```

### Running the app locally

```bash
composer start
```

Open http://localhost:8000/?user=DenverCoder1 to run the project locally

Open http://localhost:8000/demo/ to run the demo site

### Running the tests

Before you can run tests, PHPUnit must be installed. You can install it using Composer by running the following command.

```bash
composer install
```

Run the following command to run the PHPUnit test script which will verify that the tested functionality is still working.

```bash
composer test
```
