# Symfony application in Docker

[![pipeline status](https://gitlab.naitways.org/A.Fontaine/template-symfony/badges/master/pipeline.svg)](https://gitlab.naitways.org/A.Fontaine/template-symfony/commits/master)

## First run

After you've pulled the repo with 

```bash
git clone git@gitlab.naitways.org:A.Fontaine/template-symfony.git
```

Launch all the containers and install all the dependencies with 

```bash
make create
```

You should have your brand new Symfony application run at [http://localhost](http://localhost)

### Possible issue

It's possible that the database creation fail. If so, just launch `make create-db`.

## Use this template

### Make usage

To list all the commands available run `make`, it'll display a help.

To restart the project, `make up` should be enough to have everything running.

### Encore usage (i.e. JS and CSS management)

This template provides [Encore](https://symfony.com/doc/current/frontend.html). The build of the assets in done in background by the `encore` container.


### Database usage

The database data are persisted on the system in the `database/` folder, to avoid the recreation of the database at each start of the project. You can access the database at this address `127.0.0.1` on the `3306` port.

## Customize it

### Use the `naitways` libraries

Use just have to insert those lines in your `composer.json`:

```json
{
  "repositories": [{
    "type": "composer",
    "url": "https://satis.ntw.infra"
  }]
}
```

If this lib have a recipe in the [Naitways recipe repo](https://flex.ntw.infra), you have to insert the following configuration in your `composer.json` :

```json
{
  "extra": {
    "symfony": {
      "endpoint": "https://flex.ntw.infra"
    }
  }
}
``` 

And then, `make require naitways/your-lib`.

### Writing a library

#### Use satis

If you are building a library which should be share between many application inside Naitways, you should use the [satis](https://satis.ntw.infra).

In order to register your lib, you have to do add the git path in the `data/www/satis.ntw.infra/satis.json` on the `NTW-INFRA-DEVTOOLS01` VM.

**WARNING** Every 4 hours all the packages will be updated

#### Create a recipe

You have to insert your recipe in the [Naitways Recipes repo](https://flex.ntw.infra).
