# Advanced URL

Form widget for links: `type: advancedurl`.

The editor picks the kind of link and where it opens:

| Kind | Input | Stored |
|---|---|---|
| Page of the site | October page finder (pages, static pages, Crafter collection items, any `cms.pageLookup` type) | `type` empty, `url` the page address, `reference` the `october://` link |
| `https://`, `http://`, relative URL | text | `type` the scheme, `url` the rest |
| E-mail, phone | text | `type` `mailto:` / `tel:`, `url` the address |
| File from the media library | media finder | `type` empty, `url` the file address, `media` the media path |

Files and external URLs open in a new tab by default, everything else in the same tab
(the editor can change it). The stored keys stay `target`, `type`, `url`, `label` and
`css_classes`, so templates keep working:

```twig
<a href="{{ cta.type }}{{ cta.url }}" target="{{ cta.target }}">{{ cta.label }}</a>
```

`legacyString: true` edits a field that used to hold a plain URL string (Crafter uses it
for text fields named `*url` / `*link`). `Sikhub\AdvancedUrl\Classes\Link` converts
values and re-resolves page links (`Link::refresh()`), so a link follows a changed slug.
