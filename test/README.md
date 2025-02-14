# WordPress Unit Test integrate with Pest

This project is forked from [wordpress-test-plugin](https://github.com/adeleyeayodeji/wordpress-test-plugin)

## How to start

1. cd to your project, ex: your wordpress plugin folder
```bash
cd {YOUR_WORDPRESS_PATH}/wp-content/plugins/{YOUR_PLUGIN}
```

2. git clone this repo into your plugin folder and rename it to `test`

```bash
git clone https://github.com/j7-dev/wordpress-test-plugin.git test
```

3. cd to test folder and install dependencies

```bash
cd test
composer install
```

4. Prepare a database for testing

modify `test\phpunit.xml` to your database info

```xml
        <env name="WP_DB_NAME" value="test_empty" />
        <env name="WP_DB_USER" value="root" />
        <env name="WP_DB_PASS" value="root" />
        <env name="WP_DB_HOST" value="localhost:10097" />
```

