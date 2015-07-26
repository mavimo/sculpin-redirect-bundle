# Sculpin Redirect Bundle

## Setup

Add this bundle in your ```sculpin.json``` file:

```json
{
    // ...
    "require": {
        // ...
        "mavimo/sculpin-redirect-bundle": "@dev"
    }
}
```

and install this bundle running ```sculpin update```.

Now you can register the bundle in ```SculpinKernel``` class available on ```app/SculpinKernel.php``` file:

```php
class SculpinKernel extends \Sculpin\Bundle\SculpinBundle\HttpKernel\AbstractKernel
{
    protected function getAdditionalSculpinBundles()
    {
        return array(
           'Mavimo\Sculpin\Bundle\RedirectBundle\SculpinRedirectBundle'
        );
    }
}
```

## How to use

In content that you import you can setup redirect items using:

```
redirect:
    - alias-path.html
    - old-path.html
```

Then create a ```redirect.html``` file in your theme with the following content:

```html
<!DOCTYPE html>
{% spaceless %}
{% set destination = page.destination.url|default(page.destination) %}
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="refresh" content="0;url={{ destination }}" />
</head>
<body>
<p>Redirecting to <a href="{{ destination }}">{{ destination }}</a>...
</p>
</body>
</html>
{% endspaceless %}
```

In the above example, both /alias-path.html and /old-path.html will point to the slug of the file you are editing

If you would like to redirect to an offsite url, say /feed to feedburner, use this configuration. Content on this source will be ignored

```
---
full_redirect:
    origin: feed
    destination: "http://feeds.feedburner.com/timbroder"
---
```

