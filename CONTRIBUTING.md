## Contributing Guidelines

Contributions are welcome! Feel free to open an issue or submit a pull request if you have a way to improve this project.

Make sure your request is meaningful and you have tested the app locally before submitting a pull request.

This documentation contains a set of guidelines to help you during the contribution process.

### Need some help regarding the basics?🤔

You can refer to the following articles on basics of Git and Github,
in case you are stuck:

- [Forking a Repo](https://help.github.com/en/github/getting-started-with-github/fork-a-repo)
- [Cloning a Repo](https://help.github.com/en/desktop/contributing-to-projects/creating-an-issue-or-pull-request)
- [How to create a Pull Request](https://opensource.com/article/19/7/create-pull-request-github)
- [Getting started with Git and GitHub](https://towardsdatascience.com/getting-started-with-git-and-github-6fcd0f2d4ac6)
- [Learn GitHub from Scratch](https://lab.github.com/githubtraining/introduction-to-github)

### Installing Requirements

#### Requirements

* [PHP 8.0+](https://www.apachefriends.org/index.html)
* [Composer](https://getcomposer.org)
* [Imagick](https://www.php.net/imagick)

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

### Install dependencies
Run the following command to install all the required dependencies to work on this project.

```bash
composer install
```

### Running the app locally

```bash
composer start
```

Open http://localhost:8000/?user=DenverCoder1 to run the project locally

Open http://localhost:8000/demo/ to run the demo site

### Running the tests

Run the following command to run the PHPUnit test script which will verify that the tested functionality is still working.

```bash
composer test
```

## Submitting Contributions 👨‍💻

Below you will find the process and workflow used to review and merge your changes.

### Step 0 : Find an issue

- Take a look at the Existing Issues or create your **own** Issues!
- Wait for the Issue to be assigned to you after which you can start working on it.

![SC1](https://user-images.githubusercontent.com/63443481/136185624-24447858-de8d-4b0a-bb6b-2528d9031196.PNG)


### Step 1 : Fork the Project

- Fork this Repository. This will create a Local Copy of this Repository on your Github Profile.
Keep a reference to the original project in `upstream` remote.  

```bash
git clone https://github.com/<your-username>/github-readme-streak-stats.git  
cd github-readme-streak-stats  
git remote add upstream https://github.com/DenverCoder1/github-readme-streak-stats.git  
```  

![SC2](https://user-images.githubusercontent.com/63443481/136185816-0b6770d7-0b00-4951-861a-dd15e3954918.PNG)
  

- If you have already forked the project, update your copy before working.

```bash
git remote update
git checkout <branch-name>
git rebase upstream/<branch-name>
```  

### Step 2 : Branch

Create a new branch. Use its name to identify the issue your addressing.

```bash
# It will create a new branch with name Branch_Name and switch to that branch 
git checkout -b branch_name
```

### Step 3 : Work on the issue assigned

- Work on the issue(s) assigned to you.
- Make all the necessary changes to the codebase.
- After you've made changes or made your contribution to the project add changes to the branch you've just created by:

```bash  
# To add all new files to branch Branch_Name  
git add .  

# To add only a few files to Branch_Name
git add <some files (with path)>
```

### Step 4 : Commit

- To commit give a descriptive message by:

```bash
# This message get associated with all files you have changed  
git commit -m "message"  
```

### Step 5 : Work Remotely

- Now you are ready to your work to the remote repository.
- When your work is ready and complies with the project conventions, upload your changes to your fork:

```bash  
# To push your work to your remote repository
git push -u origin Branch_Name
```

- Here is how your branch will look.
![SC3](https://user-images.githubusercontent.com/63443481/136186235-204f5c7a-1129-44b5-af20-89aa6a68d952.PNG)

### Step 6 : Pull Request

- Go to your repository in browser and click on compare and pull requests.
Then add a title and description to your pull request that explains your contribution.  
<img width="700" alt="pr" src="https://user-images.githubusercontent.com/63443481/136186304-c0a767ea-1fd2-4b0c-b5a8-3e366ddc06a3.PNG">  

<img width="882" alt="pullr" src="https://user-images.githubusercontent.com/63443481/136186322-bfd5f333-136a-4d2f-8891-e8f97c379ba8.PNG">  

- Voila! Your Pull Request has been submitted and it's ready to be merged.🥳 <br />

#### Happy Contributing!
