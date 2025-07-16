# Nice Learning Theme

**Nice Learning** is a completely free custom theme for Moodle 5.x. It’s clean, user-friendly, and fully compatible with right-to-left (RTL) languages. The theme also comes with a set of custom blocks for extended functionality and improved user experience.

[View full documentation →](https://docs.nicelearning.org/getting-started/overview)

---

## Features

- Fully responsive design
- RTL language support
- Customizable brand colors
- Compatible with Moodle 5.x

---

## Installation

### Install the Theme Plugin

1. Go to: Site administration → Plugins → Install plugins
2. Choose **Install plugin from ZIP file.**
3. Upload the ZIP file named `nice.zip`.
4. Click **Install plugin from the ZIP file.**
5. Complete the installation and upgrade the Moodle database as prompted.

---

### Activate the Theme

1. Navigate to: Site administration → Appearance → Themes → Theme selector
2. Select **Nice Learning** as your active theme.

---

### Customize Branding and Settings

- Explore and adjust all available branding options and other settings for the theme.
- Refer to the [Theme Settings documentation](https://docs.nicelearning.org/theme-settings/general-settings) for detailed guidance.

---

## Installing Blocks

After installing and customizing the Nice Learning theme, you can enhance your Moodle site further by installing the custom blocks.

### Download the Blocks Package

- Download the blocks package from:

[Download Nice Learning Blocks →](https://docs.nicelearning.org/website/blocks.zip)

Inside the downloaded package, you’ll find two folders:

- `blocks_unzipped`
- `blocks_zipped`

---

### Option 1 — Install Blocks via File Copy (Bulk Installation)

1. Open the `blocks_unzipped` folder.
2. You’ll see **38 folders**, each representing a separate block.
3. Copy **all these folders** into your Moodle installation under: moodle/blocks/
4. Once copied, visit the following link in your browser to trigger Moodle’s plugin installation and database upgrade for all blocks at once: yoursiteurl/admin/index.php

---

### Option 2 — Install Blocks via Moodle Plugin Installer

1. Open the `blocks_zipped` folder.
2. Each block is provided as a separate ZIP file.
3. To install a block:
- Go to:
  
  ```
  Site administration → Plugins → Install plugins
  ```
- Upload the desired block’s ZIP file.
- Click **Install plugin from the ZIP file** and complete the installation steps, just as you did for installing the theme.

---

## License

GNU GPL v3 or later

---

## Changelog

- **v1.0**
  - Initial release
