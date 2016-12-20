# Using Couscous for LaunchCode projects

We have a customized version of Couscous, so be sure you follow the instructions here rather than directions from the main repo or couscous.io.

## Setting up

- Install [Homebrew](http://brew.sh/)
- `brew install node`
- `brew install composer`

### Requirements

Before starting with Couscous, install these requirements.

- Bower:

    ```
    $ npm install -g bower
    ```

- Less compiler:

    ```
    $ npm install -g less less-plugin-clean-css
    ```

- Phar generation enabled in `php.ini`. You can find `php.ini` by running `$ php --ini`.

    ```
    phar.readonly = Off
    ```

### Install Couscous (dev version)

Clone this repo, checkout the `lc_develop` branch, and from within the project directory run:

```
$ composer update
```
and then:
```
$ ./build.sh
```

This will build the project to `bin/couscous.phar`, then install the PHP archive to a path on your filesystem.

### Running Couscous in Preview Mode

You can run a local server to test changes before deployment by running the following command from the directory of a Couscous project:

```
$ couscous preview
```

In each LaunchCode Couscous site, you can run this command to deploy:

```
$ ./update-website.sh
```

If you want to work on a project's template, you can do so using preview mode and by change the template location in `couscous.yml`:

```
template:
    # directory: ../Template-Light
```
