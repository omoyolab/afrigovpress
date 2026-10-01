// Makes afrigovpress-<version>.zip, ready for Appearance, Themes, Add new, Upload.
import { execFileSync } from "node:child_process";
import { readFileSync, rmSync } from "node:fs";
import { dirname, join } from "node:path";
import { fileURLToPath } from "node:url";

const ROOT = join(dirname(fileURLToPath(import.meta.url)), "..");
const version = /Version:\s*([0-9.]+)/.exec(readFileSync(join(ROOT, "afrigovpress", "style.css"), "utf8"))[1];
const out = `afrigovpress-${version}.zip`;
rmSync(join(ROOT, out), { force: true });
execFileSync("zip", ["-r", "-q", out, "afrigovpress", "-x", "*.DS_Store"], { cwd: ROOT });
console.log(out);
