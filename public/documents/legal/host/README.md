# Host waiver PDF files

Place your two PDF files in this folder using these exact names (or set custom names in `.env`):

| File | Purpose |
|------|---------|
| `host-nda.pdf` | Section 6 — Standard Non-disclosure Agreement |
| `host-independent-contractor-agreement.pdf` | Section 7 — Independent Contractor Agreement |

Optional `.env` overrides:

```
LEGAL_HOST_NDA_PDF=your-nda-filename.pdf
LEGAL_HOST_CONTRACTOR_PDF=your-contractor-filename.pdf
```

After uploading, visit `/waiver-of-liability-host` — each section shows an embedded viewer plus **Open PDF** and **Download PDF** buttons.
