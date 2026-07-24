# Vue 3 + WordPress Headless Boilerplate

> **Historical public reference.** This repository accompanies a Hebrew
> lecture about combining a Vue front end with WordPress as a headless CMS. It
> is not actively maintained, and its historical dependency set should not be
> used unchanged in production.

For the July 2026 repository restoration record, see
[HISTORY.md](HISTORY.md).

## What this demonstrates

- a Vue 3 and Vite front end;
- WordPress used as a headless CMS;
- Yarn workspaces for the application packages;
- a Docker Compose development environment with WordPress, MySQL, and
  phpMyAdmin;
- a mounted `wp-content` extension surface for local WordPress development.

## Architecture

```text
Browser
   |
Vue 3 / Vite front end
   |
WordPress REST API
   |
WordPress + MySQL
```

The root workspace contains:

- `apps/front`: Vue application and its unit/end-to-end test setup;
- `apps/wp-headless`: WordPress content mounted into the local environment;
- `docker-compose.yml`: historical local service composition.

## Lecture

[Watch the original Hebrew lecture on YouTube](https://www.youtube.com/watch?v=0HoCSbbde8I).

## Local development

Requirements: Yarn 1, Docker, and Docker Compose compatible with this
historical configuration. The Compose stack uses unpinned `latest` images and
should be reviewed before it is run on a current Docker installation.

```bash
git clone https://github.com/Mosharush/wp-vue-boilerplate.git
cd wp-vue-boilerplate
yarn install --frozen-lockfile
yarn start:all
```

`start:all` starts the Docker services and then runs the Vue/Vite client
separately. By default, Vite listens on port `3000`, WordPress on `8000`, and
phpMyAdmin on `8080`.

These development services bind to all interfaces by default. Run them only on
a trusted development machine and do not expose their ports to an untrusted
network.

Useful commands:

```bash
yarn start:docker  # start the Docker services
yarn start:client  # start only the Vue/Vite client
yarn stop          # stop the Docker services
```

## Verification

The restored tree was checked with a frozen Yarn 1 install (lifecycle scripts
disabled), a Vite production build, and the existing unit-test suite.

## Security and maintenance status

- The repository is preserved for learning and historical reference.
- Dependencies are old and GitHub currently reports unresolved Dependabot
  alerts; review and upgrade them before reuse.
- The Docker, database, TLS, and local-service defaults are not current
  production security guidance.
- Do not expose the development services to an untrusted network.

## Contributing

The project is not under active feature development. Focused documentation or
security-maintenance fixes may still be reviewed through pull requests.

## License

Available under the [MIT license](LICENSE).
