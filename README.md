## WebLorem WordPress starter project

<p>
  <a href="https://roots.io/bedrock/">
    <img alt="WebLorem logo" src="https://weblorem.com/wp-content/uploads/2022/04/logo.svg" height="70">
  </a>
</p>

<p>
    <img src="https://img.shields.io/badge/theme-v2.0-informational" alt="Version">
</p>

## Requirements

- PHP >= 7.4
- Composer - [Install](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx)

## Installation

1. Clone project:
   ```sh
   git clone
   composer install
   ```
2. Update environment variables in the `.env` file. Wrap values that may contain non-alphanumeric characters with quotes, or they may be incorrectly parsed.

    - Database variables
      - `DB_NAME` - Database name
      - `DB_USER` - Database user
      - `DB_PASSWORD` - Database password
      - `DB_HOST` - Database host
      - Optionally, you can define `DATABASE_URL` for using a DSN instead of using the variables above (e.g. `mysql://user:password@127.0.0.1:3306/db_name`)
    - `WP_ENV` - Set to environment (`development`, `staging`, `production`)
    - `WP_HOME` - Full URL to WordPress home (https://example.com)
    - `WP_SITEURL` - Full URL to WordPress including subdirectory (https://example.com/wp)
    - `AUTH_KEY`, `SECURE_AUTH_KEY`, `LOGGED_IN_KEY`, `NONCE_KEY`, `AUTH_SALT`, `SECURE_AUTH_SALT`, `LOGGED_IN_SALT`, `NONCE_SALT`
      - Generate with [wp-cli-dotenv-command](https://github.com/aaemnnosttv/wp-cli-dotenv-command)
      - Generate with [our WordPress salts generator](https://roots.io/salts.html)

3. Access WordPress admin at `https://example.com/wp/wp-admin/`

### File Structure in WebLorem theme
 
<details>
<summary>assets</summary>
- <b>assets</b> folder holds all project's resource files
</details>
<details>
<summary>inc</summary>
- <b>inc</b> folder is the place for all PHP functions of the theme. The functions.php just includes all these files
</details>
<details>
<summary>languages</summary>
- <b>languages</b> folder the place for files with translation of the theme into other languages 
</details>
<details>
<summary>template-pages</summary>
- <b>template-pages</b> folder contains the page template files 
</details>
<details>
<summary>template-parts</summary>
- <b>template-parts</b> folder contains the files, which display parts of the theme and are included in other files
</details>

## Original Bedrock documentation

Bedrock documentation is available at [https://roots.io/docs/bedrock/master/installation/](https://roots.io/docs/bedrock/master/installation/).
