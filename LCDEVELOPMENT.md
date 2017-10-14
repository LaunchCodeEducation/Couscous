# Using Couscous for LaunchCode projects

We have a customized version of Couscous, so be sure you follow the instructions here rather than directions from the main repo or couscous.io.

## Setting up

- Install [Homebrew](http://brew.sh/)
- `brew install node`
- `brew install homebrew/php/composer`
    ```
- Make sure you have phar (PHP archive) generation enabled in your `php.ini`. You can find the location of your `php.ini` by running `$ php --ini`. Then ensure the following setting is enabled:

    ```
    phar.readonly = On
    ```

### Install Couscous (dev version)

Clone this repo, checkout the `lc_develop` branch. Now we will build the Couscous phar/executable and place it in our path.

Pull in dependencies:
```
$ composer update
```

And then:

#### Mac users

```
$ ./build.sh
```
This script will build the project to `bin/couscous.phar`, then install the PHP archive to a path on your filesystem.

#### Windows users

```
$ bin/compile
```

Remove the extension from the built `bin/couscous.phar` and add the full path to `bin/couscous` to your Path.

### Running Couscous in Preview Mode

You can run a local server to test changes before deployment by running the following command from the directory of a Couscous project:

```
$ couscous preview
```

In each LaunchCode Couscous site, you can run this command to deploy:

```
$ couscous deploy
```

### Notes

- All work should be done in `master`. When running `couscous deploy`, the markdown files will be built into HTML files using the configured template, into a temporary directory. The contents of that build will be committed and pushed to the `gh-pages` branch for hosting.
- In the `master` branch, files correspond to path segments in the built site, and `.md` files to `.html` files. In most cases, `some-directory/FILE.md` will be built into `some-directory/file.html`, however all `README.md` files are built into `index.html`.
- Some of the LaunchCode Education sites built with Couscous:
    - [Java Skill Track](https://github.com/LaunchCodeEducation/skills-back-end-java)
    - [Web Fundamentals (Unit 2)](https://github.com/LaunchCodeEducation/web-fundamentals)
    - [Coding Prep (Unit 0)](https://github.com/LaunchCodeEducation/coding-prep)
    - [Liftoff](https://github.com/LaunchCodeEducation/liftoff)
    This is not an comprehensive list. To determine if a site/repo is a Couscous-generated site, look for a `couscous.yml` file.
- Couscous Generates pages from a template. Ours is [a fork of TemplateLight](https://github.com/LaunchCodeEducation/Template-Light) that has been customized. If you want to contribute to or modify that template, talk to a LaunchCode Curriculum Team member.
- Some additional details on working on LC sites with Couscous can be found on the [internal docs](http://education.launchcode.org/internal-docs/) page. In particular, you'll likely find [aside boxes](http://education.launchcode.org/internal-docs/aside-boxes/) useful.
