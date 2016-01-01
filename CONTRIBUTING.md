# CONTRIBUTING

## RESOURCES

If you wish to contribute to dbwrapper, please be sure to
read/subscribe to the following resources:

* [PSR 4 Autoloader](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md)
* [PSR 2 Coding Style Guide](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)
* [PSR 1 Coding Standards](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md)
* [PSR 0 Autoloading standards](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md)

If you are working on new features or refactoring [create a proposal](https://github.com/belgattitude/dbwrapper/issues/new).

## Reporting Issues

When reporting issues, please provide the following information:

- A description indicating how to reproduce the issue

## RUNNING TESTS

Prerequisites:

- Install [composer](https://getcomposer.org/).

To run tests:
  
- Install phpunit

  ```console
  $ composer global require "phpunit/phpunit=*"
  ```

  If not already done, ensure that your `~/.bashrc` or `~/.bash_profile` contains
  the composer bins path.

  ```console
  $ vi ~/.bashrc
      -> export PATH=~/.composer/vendor/bin:$PATH
  ```

- Clone the repository:

  ```console
  $ git clone git@github.com:belgattitude/dbwrapper.git
  $ cd dbwrapper
  ```

- Install dependencies via composer:

  ```console
  $ composer install
  ```

- Install MySQL test database

  ```console
  $ mysql -e "DROP DATABASE IF EXISTS phpunit_soluble_schema_db;" -u root
  $ mysql -e "CREATE DATABASE phpunit_soluble_schema_db;" -u root -p
  $ zcat test/data/mysql/schema.sql.gz | mysql -u root -p phpunit_soluble_schema_db
  $ zcat test/data/mysql/data.sql.gz | mysql -u root -p phpunit_soluble_schema_db
  ```

- Prepare the phpunit.xml configuration and run the tests

  ```console
  $ cp phpunit.xml.dist phpunit.xml
  $ phpunit
  ```

- If you want to enable dynamically xdebug for code coverage :

  ```console
  $ php -d zend_extension=xdebug.so  ~/.composer/vendor/bin/phpunit
  ```

- Or test it with HHVM

  ```console
  $ hhvm ~/.composer/vendor/bin/phpunit
  ```

## Running Coding Standards Checks

This component uses [phpcs](https://github.com/squizlabs/PHP_CodeSniffer) for coding
standards checks, and provides configuration for our selected checks.
`phpcs` is installed by default via Composer.

- Install phpcs

  ```console
  $ composer global require "squizlabs/php_codesniffer=*"
  ```

  Run style checks

  ```console
  $ ./vendor/bin/phpcs
  ```

  `phpcs` also includes a tool for fixing most CS violations, `phpcbf`:

  ```console
  $ ./vendor/bin/phpcbf
  ```

If you allow `phpcbf` to fix CS issues, please re-run the tests to ensure
they pass, and make sure you add and commit the changes after verification.

## Generate api

Install ApiGen globally

  ```console
  composer global require --dev apigen/apigen
  ```

Run ApiGen in the project root folder

  ```console
  ~/.composer/vendor/bin/apigen generate --config=./.apigen.yml
  ```

Generated API should be available in doc/api folder

## Recommended Workflow for contributions

Your first step is to establish a public repository from which we can
pull your work into the master repository. We recommend using
[GitHub](https://github.com), as that is where the component is already hosted.

1. Setup a [GitHub account](http://github.com/), if you haven't yet
2. Fork the repository (http://github.com/belgattitude/dbwrapper)
3. Clone the canonical repository locally and enter it.

   ```console
   $ git clone git://github.com:belgattitude/dbwrapper.git
   $ cd dbwrapper
   ```

4. Add a remote to your fork; substitute your GitHub username in the command
   below.

   ```console
   $ git remote add {username} git@github.com:{username}/dbwrapper.git
   $ git fetch {username}
   ```

### Keeping Up-to-Date

Periodically, you should update your fork or personal repository to
match the canonical soluble repository. Assuming you have setup your local repository
per the instructions above, you can do the following:


```console
$ git checkout master
$ git fetch origin
$ git rebase origin/master
# OPTIONALLY, to keep your remote up-to-date -
$ git push {username} master:master
```

If you're tracking other branches -- for example, the "develop" branch, where
new feature development occurs -- you'll want to do the same operations for that
branch; simply substitute  "develop" for "master".

### Working on a patch

We recommend you do each new feature or bugfix in a new branch. This simplifies
the task of code review as well as the task of merging your changes into the
canonical repository.

A typical workflow will then consist of the following:

1. Create a new local branch based off either your master or develop branch.
2. Switch to your new local branch. (This step can be combined with the
   previous step with the use of `git checkout -b`.)
3. Do some work, commit, repeat as necessary.
4. Push the local branch to your remote repository.
5. Send a pull request.

The mechanics of this process are actually quite trivial. Below, we will
create a branch for fixing an issue in the tracker.

```console
$ git checkout -b hotfix/9295
Switched to a new branch 'hotfix/9295'
```

... do some work ...


```console
$ git commit
```

... write your log message ...


```console
$ git push {username} hotfix/9295:hotfix/9295
Counting objects: 38, done.
Delta compression using up to 2 threads.
Compression objects: 100% (18/18), done.
Writing objects: 100% (20/20), 8.19KiB, done.
Total 20 (delta 12), reused 0 (delta 0)
To ssh://git@github.com/{username}/dbwrapper.git
   b5583aa..4f51698  HEAD -> master
```

To send a pull request, you have two options.

If using GitHub, you can do the pull request from there. Navigate to
your repository, select the branch you just created, and then select the
"Pull Request" button in the upper right. Select the user/organization
"soluble" as the recipient.


#### What branch to issue the pull request against?

Which branch should you issue a pull request against?

- For fixes against the stable release, issue the pull request against the
  "master" branch.
- For new features, or fixes that introduce new elements to the public API (such
  as new public methods or properties), issue the pull request against the
  "develop" branch.

### Branch Cleanup

As you might imagine, if you are a frequent contributor, you'll start to
get a ton of branches both locally and on your remote.

Once you know that your changes have been accepted to the master
repository, we suggest doing some cleanup of these branches.

-  Local branch cleanup

   ```console
   $ git branch -d <branchname>
   ```

-  Remote branch removal

   ```console
   $ git push {username} :<branchname>
   ```
