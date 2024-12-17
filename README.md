# BentoHook

A FormIt hook for Bento marketing platform.

## System Settings

| Key                       | Description                |
|---------------------------|----------------------------|
| bentohook.secret-key      | Secret Key from Bento      |
| bentohook.publishable-key | Publishable Key from Bento |
| bentohook.site-uuid       | Site UUID from Bento       |

## Usage


### FormIt

To add a Bento subscription to a FormIt form, add the `BentoHook` hook to the form.

```html
[[!FormIt?
    &hooks=`BentoHook`
```

Additional parameters can be passed to the hook to customize the subscription.

```html
[[!FormIt?
    &hooks=`BentoHook`
    &bentoEmail=`email` // The email field name
    &bentoOptin=`optin` // An optin field name
    &bentoOptout=`optout` // An optout field name
    &bentoFields=`beno_field_label==formit_field_name` // Custom fields to send to Bento
```

### Profiles

To check if use logged in user is subscribed to Bento, use the `BentoHook` snippet.

```html
[[!BentoCheckList
   &email=`test@example.com` // The email to check
   &inListTpl=`inListTpl` // Chunk to display if user is subscribed
   &notInListTpl=`notInListTpl` // Chunk to display if user is not subscribed
]]
```

### References

- https://docs.bentonow.com/
- https://github.com/bentonow/bento-php-sdk
