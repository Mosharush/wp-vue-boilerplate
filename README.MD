# VueJs + Wordpress Headless Boilerplate

[![LICENSE](https://img.shields.io/badge/license-MIT-lightgrey.svg)](https://github.com/Mosharush/wp-vue-boilerplate/blob/master/LICENSE.txt)
[![github-stars-image](https://img.shields.io/github/stars/Mosharush/wp-vue-boilerplate.svg?label=github%20stars)](https://github.com/Mosharush/wp-vue-boilerplate)

---

VueJs + Wordpress Headless Boilerplate is a project template for building
fast, robust, and adaptable web apps or sites based on VueJS for the front-end & Wordpress as Headless CMS for the back-end.

This project is the product of lecture on Zoom, in Hebrew language.

## Recording Video of the lecture on Youtube

[![VueJs + Wordpress Headless on YouTube](https://img.youtube.com/vi/0HoCSbbde8I/0.jpg "VueJs + Wordpress Headless on YouTube")](https://www.youtube.com/watch?v=0HoCSbbde8I)

Source files: [https://github.com/Mosharush/wp-vue-boilerplate](https://github.com/Mosharush/wp-vue-boilerplate)

## Quick start

- Clone the git repo — `git clone https://github.com/Mosharush/wp-vue-boilerplate.git`
- Open the project with your favorite IDE — ex: `codium wp-vue-boilerplate`
- Install all dependencies with Yarn — `yarn install`
- Start All dev servers (include Dockers services) — `yarn start:all`
- All is ready! Go to [http://localhost](http://localhost) to view your app or Go to your [`WordPress Admin Dashboard`](http://localhost/wp-admin/).

## Features

- A finely-tuned starter template to development End-to-End Web application.
- A Starter build of `VueJs` App integration with `WordPress` Headless.
- [`Docker`](https://docs.docker.com/get-started/overview/)
  for servers virtualization and quick startup.
- [`MySQL Server`](https://dev.mysql.com/doc/refman/8.0/en/what-is-mysql.html)
  For a local DataBase to store `WordPress` data.
  - `PhpMyAdmin` Web-App to access `MySQL` Data via GUI.
- `wp-content` directory volume access included A `headless` plugin to write your custom code to integrate with `WordPress` Hooks and more...
  - Feature Ongoing: `wp-config.php` manipulate.
- An example global Yarn (worksapces) package.json file with commands
  built in to jumpstart application development

## Commands

Install all dependencies with Yarn (not NPM, we using Yarn workspaces)

```
yarn install
```

- Post install action: we generate a SSL certificate for `localhost` domain to support https connections. you can change the registered domains on `nginx/ssl/v3.ext` file and run generate `yarn generate:ssl`

Start All Servers:

```
yarn start:all
```

Start Only `Docker` Services with `Docker Compose`:

```
yarn start:docker
```

Stop docker containers:

```
yarn stop
```

Start Only `Vue App` Dev Server (HMR):

```
yarn start:client
```

## Contributing

Hundreds of developers have helped to make the HTML5 Boilerplate. Anyone is
welcome to [contribute](.github/CONTRIBUTING.md), however, if you decide to get
involved, please take a moment to review the [guidelines](.github/CONTRIBUTING.md):

- [Bug reports](.github/CONTRIBUTING.md#bugs)
- [Feature requests](.github/CONTRIBUTING.md#features)
- [Pull requests](.github/CONTRIBUTING.md#pull-requests)

## License

The code is available under the [MIT license](LICENSE.txt).
