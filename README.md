# Tinkerwell Workbench

![Tinkerwell PHP REPL](https://tinkerwell.app/images/simple_screenshot.png?v4-1)

www.tinkerwell.app

Tinkerwell is a REPL on steroids. It allows you to run code snippets within the context of your application without
hitting the browser. It's the perfect companion to your favorite IDE and works with any framework – locally, via SSH,
Docker and even on Laravel Vapor.

This package contains helpful tools to customize Tinkerwell for your application.

**You do not need to install this package to customize Tinkerwell. Just copy the driver or tool you need into your
applications' [custom driver](https://tinkerwell.app/docs/4/extending-tinkerwell/custom-drivers). However, if you want
to make use of
autocompletion while implementing your customization, it's helpful to install this package as a dev dependency.**

```
composer require --dev beyondcode/tinkerwell
```

## Drivers

When you open your project with Tinkerwell, one of the available drivers will be loaded and bootstrap your application
to prepare it for code execution within Tinkerwell. This repository holds all available drivers for Tinkerwell. If your
framework does not have a
specific driver yet, Tinkerwell will at least try and load your projects autoload file.
If you have written a custom driver for a framework by yourself, feel free to open a PR and add it to this repository!

For more information about drivers, check out
the [documentation](https://tinkerwell.app/docs/4/extending-tinkerwell/custom-drivers).

## Panels

With Panels, Tinkerwell offers a concise and visually appealing way to get a snapshot of specific details about your
application.

For more information about panels, check out
the [documentation](https://tinkerwell.app/docs/4/extending-tinkerwell/panels).

## Themes
If you want to add a custom theme to Tinkerwell, you can find two example themes in the `src/themes` directory.

For more information about custom themes, check out
the [documentation](https://tinkerwell.app/docs/4/advanced-usage/custom-themes).

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email support@beyondco.de instead of using the issue tracker.

## Credits

- [Beyond Code](https://github.com/beyondcode)
- [Diana Scharf](https://github.com/mechelon)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
