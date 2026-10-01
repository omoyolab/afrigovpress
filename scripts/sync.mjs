// Copies afrigov's built files into the theme and regenerates what is derived from it:
// the pack data the PHP reads, and the editor presets in theme.json. Run after bumping afrigov.
import { cpSync, mkdirSync, readFileSync, readdirSync, rmSync, writeFileSync } from "node:fs";
import { dirname, join } from "node:path";
import { fileURLToPath } from "node:url";

const ROOT = join(dirname(fileURLToPath(import.meta.url)), "..");
const SRC = join(ROOT, "node_modules", "afrigov");
const THEME = join(ROOT, "afrigovpress");
const OUT = join(THEME, "assets", "afrigov");
const version = JSON.parse(readFileSync(join(SRC, "package.json"), "utf8")).version;

rmSync(OUT, { recursive: true, force: true });
mkdirSync(OUT, { recursive: true });
const dist = join(SRC, "dist");
const codes = readdirSync(dist).filter((f) => /^[a-z]{2}\.json$/.test(f)).map((f) => f.slice(0, 2));
for (const file of ["core.min.css", "afrigov.iife.js", ...codes.map((c) => `${c}.min.css`)]) cpSync(join(dist, file), join(OUT, file));
cpSync(join(dist, "flags"), join(OUT, "flags"), { recursive: true });

// Pack data for PHP: only what the theme uses.
const packs = {};
for (const code of codes) {
  const p = JSON.parse(readFileSync(join(dist, `${code}.json`), "utf8"));
  packs[code] = {
    country: p.country,
    government: p.government,
    domain: p.domain,
    language: p.language ?? "en",
    direction: p.direction ?? "ltr",
    flag: p.flag,
    flagDirection: p.flagDirection ?? "row",
    flagSvg: p.flagSvg ? `flags/${code}.svg` : null,
    strings: p.strings,
  };
}
writeFileSync(join(THEME, "inc", "packs-data.json"), JSON.stringify({ afrigov: version, packs }, null, 2) + "\n");

// theme.json presets from the tokens. Values are afrigov's custom properties, so the chosen pack colours the editor too.
const tokens = JSON.parse(readFileSync(join(SRC, "tokens", "core.tokens.json"), "utf8"));
const names = (group) => Object.keys(tokens[group]).filter((k) => !k.startsWith("$"));
const title = (s) => s.replace(/-/g, " ").replace(/^./, (c) => c.toUpperCase());
const palette = names("color").map((k) => ({ slug: k, name: title(k), color: `var(--ag-color-${k})` }));
const fontSizes = names("font").filter((k) => k.startsWith("size-")).map((k) => ({ slug: k.slice(5), name: k.slice(5).toUpperCase(), size: `var(--ag-font-${k})` }));
const spacing = names("space").map((k) => ({ slug: k, name: k, size: `var(--ag-space-${k})` }));
const themeJson = {
  $schema: "https://schemas.wp.org/trunk/theme.json",
  version: 2,
  settings: {
    appearanceTools: false,
    layout: { contentSize: "var(--ag-size-measure)", wideSize: "var(--ag-size-container)" },
    color: { defaultPalette: false, defaultGradients: false, defaultDuotone: false, custom: false, customGradient: false, customDuotone: false, palette },
    typography: { defaultFontSizes: false, customFontSize: false, dropCap: false, fontStyle: false, letterSpacing: false, textDecoration: false, textTransform: false, fontSizes },
    spacing: { defaultSpacingSizes: false, customSpacingSize: false, spacingSizes: spacing, units: ["rem", "%"] },
    border: { color: false, radius: false, style: false, width: false },
    shadow: { defaultPresets: false },
  },
};
writeFileSync(join(THEME, "theme.json"), JSON.stringify(themeJson, null, 2) + "\n");
console.log(`afrigov ${version} synced: ${codes.length} packs (${codes.join(", ")}), theme.json with ${palette.length} colours, ${fontSizes.length} sizes.`);
