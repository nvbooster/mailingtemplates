# E-mail templates helper

## Install

```bash
git clone emailtemplates
cd emailtemplates
composer install
yarn install
```

## Create template

Start with copying html template

```bash
cp templates/action.html.twig templates/myemail.html.twig
```

Copy styles

```bash
cp -r assets/less/action/ assets/less/myemail
```

Add new entries to `webpack.config.js` with the same name as template

```js
...
.addStyleEntry('css/myemail', './assets/less/myemail/style.less')
.addStyleEntry('css/myemail.r', './assets/less/myemail/responsive.less')
...
```

Require template images in `assets/js/dev.js` if any

## Develop

Run symfony in your web-server of choice or use php built-in webserver

```bash
php -S localhost:8000 -t public/
```

> Warning! Built-in server works only with using webpack dev-server. Trying to inline styles with static built styles will block web-server.
  
 
Run webpack dev-server

```bash
yarn dev-server
```

Browse to your template, base style and responsive style will be injected separatly

- `http://localhost:8000/myemail`
- `http://localhost:8000/myemail?inline`
- `http://localhost:8000/myemail?noresponsive`
- `http://localhost:8000/myemail?noresponsive&inline`

Add `noresponsive` parameter to skip responsive style  
Add `inline` parameter to inline styles. Responsive style will be injected as `<style>` tag  
 
## Compile
Run webpack

```bash
yarn build
```

Include `myemail.r.css` as style tag in template and use `myemail.css` as style for inlining 
