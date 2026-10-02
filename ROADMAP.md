# Roadmap

## 0.1 Foundation (done)

- A hybrid theme: fixed templates for the banner, header and footer; page content from the block editor or the classic editor.
- Country packs, the organisation's name and crest, the address and social accounts as site settings.
- Page, news list, article, search and page-not-found templates.
- A description, sharing tags and structured data for every page.
- afrigov synced in from the npm package, with the editor's presets generated from its tokens.

## 0.2 Patterns (done)

- Sections as editor patterns: hero, hero with image, centred hero, band with cards, band with a call to act, service cards, statement, latest news, questions, alert, downloads.
- A whole home page pattern, and a landing page template with no automatic title.
- Variants in the Styles panel for paragraphs, buttons, groups, tables and lists.

Still to come from this milestone: a Formats menu in the classic editor, and a gallery album pattern.

## 0.3 Languages and forms

- Right to left, and the language switcher filled by Polylang or WPML.
- Styles for the search and comment forms and the most used form plugins.

## 1.0

- Submitted to the WordPress.org theme directory with the accessibility-ready tag.

## Decisions

- **One theme for both editors.** The header, banner and footer cannot be broken from the editor.
- **afrigov is bundled with the theme.** A government site should not depend on someone else's server.
- **GPL v2 or later**, as WordPress themes must be. afrigov stays MIT and is included under that licence.
- **Page-builder content is not converted.** Posts, pages written in the editor, menus and media carry over when the theme is switched; pages made in a builder are rebuilt from patterns.
