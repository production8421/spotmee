"""One-off: convert Cuba starter-kit index.html sections to Blade partials."""
import re
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
SRC = Path(r"C:\New folder\htdocs\New folder\cuba_dashboard_template\starter-kit\index.html")
OUT = ROOT / "resources/views/cuba/partials"

text = SRC.read_text(encoding="utf-8")


def cuba_urls(s: str) -> str:
    def repl(m: re.Match) -> str:
        path, frag = m.group(1), m.group(2) or ""
        return "{{ $cubaAsset('" + path + "') }}" + frag

    return re.sub(r'\.\./assets/([^"\s#]+)(#[^\s"]*)?', repl, s)


s = cuba_urls(text)
s = s.replace('href="index.html"', 'href="{{ route(\'dashboard\') }}"')
s = re.sub(r'href="\.\./template/[^"]+"', 'href="#"', s)

head_start = s.index("<head>")
head_end = s.index("</head>") + len("</head>")
head_block = s[head_start:head_end]

# Scripts at bottom: from latest jquery to end body
script_start = s.index("<!-- latest jquery-->")
script_end = s.rindex("</body>")
scripts_block = s[script_start:script_end]

# Header: Page Header Start through Page Header Ends
h0 = s.index("<!-- Page Header Start-->")
h1 = s.index("<!-- Page Header Ends", h0)
header_block = s[h0:h1] + "\n      <!-- Page Header Ends                              -->"

# Sidebar
s0 = s.index("<!-- Page Sidebar Start-->")
s1 = s.index("<!-- Page Sidebar Ends", s0)
sidebar_block = s[s0:s1] + "\n        <!-- Page Sidebar Ends-->"

# Simplify sidebar: replace external dashboard link with route
sidebar_block = sidebar_block.replace(
    'href="../template/index.html" target="_blank"',
    'href="{{ route(\'dashboard\') }}"',
)

# Footer
f0 = s.index("<!-- footer start-->")
f1 = s.index("</footer>", f0) + len("</footer>")
footer_block = s[f0:f1]

OUT.mkdir(parents=True, exist_ok=True)

(OUT / "head.blade.php").write_text(
    "@php\n    /** @var callable(string): string \$cubaAsset */\n@endphp\n" + head_block + "\n",
    encoding="utf-8",
)
(OUT / "header.blade.php").write_text(
    "@php\n    /** @var callable(string): string \$cubaAsset */\n@endphp\n" + header_block + "\n",
    encoding="utf-8",
)
(OUT / "sidebar.blade.php").write_text(
    "@php\n    /** @var callable(string): string \$cubaAsset */\n@endphp\n" + sidebar_block + "\n",
    encoding="utf-8",
)
(OUT / "footer.blade.php").write_text(footer_block + "\n", encoding="utf-8")
(OUT / "scripts.blade.php").write_text(
    "@php\n    /** @var callable(string): string \$cubaAsset */\n@endphp\n" + scripts_block + "\n",
    encoding="utf-8",
)

print("Wrote partials to", OUT)
