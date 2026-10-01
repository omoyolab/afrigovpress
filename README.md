# afrigovPress

The [afrigov](https://github.com/omoyolab/afrigov) design system as a WordPress theme. Most government websites in Africa already run WordPress, so adopting afrigov is a theme install.

The theme is the `afrigovpress/` folder. Everything else here is for building and testing it.

## What you get

- An official website banner, header, navigation and footer that an editor cannot break.
- Country packs as a site setting: the colours, the flag and the banner's words.
- News as a dated list with thumbnails, and articles with a lead image.
- A title, description, sharing tags and structured data for every page, written from what the editor already wrote. An SEO plugin, if active, takes over.
- Pages that score 100, A with [afrigov-audit](https://github.com/omoyolab/afrigov-audit) out of the box.

## Run it locally

Needs Docker and Node.

```sh
npm install
npm run sync     # copies afrigov into the theme and writes theme.json
npm start        # WordPress at http://localhost:8888
npm run seed     # optional: demo pages, menus and news to look at
```

Log in at http://localhost:8888/wp-admin with user `admin` and password `password`. The theme folder is mounted live: save a file and refresh.

`npm run zip` makes `afrigovpress-<version>.zip` for Appearance, Themes, Add new, Upload on any WordPress.

## How afrigov updates reach the theme

afrigov is a pinned dependency. `npm run sync` copies its built stylesheet, pack stylesheets, script and flags into `afrigovpress/assets/afrigov/`, writes the pack data the PHP reads, and regenerates the colour, type and spacing presets in `theme.json` from afrigov's tokens. Files load with the afrigov version in their address, so browsers fetch the new ones. Bump the dependency, sync, audit, release the theme; sites get it as a normal theme update.

## Licence

GPL v2 or later, as WordPress themes must be. afrigov itself is MIT and is included under that licence.

What is done and what is next is in [ROADMAP.md](ROADMAP.md).
