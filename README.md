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
generator: redirect
redirect:
    - alias-path.html
    - old-path.html
```

Then create a ```redirect.html``` file in your theme with the following content:

```html
<!DOCTYPE html>
{% spaceless %}
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="refresh" content="0;url={{ page.destination.url }}" />
  </head>
</html>
{% endspaceless %}
```

Now if a user visit the a ```alias-path.html```will be redirect on original page.
