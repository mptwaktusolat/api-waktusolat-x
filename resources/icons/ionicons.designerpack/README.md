# ionicons

Add to project instructions:

- Download the icon pack from their [official website](https://ionic.io/ionicons): https://ionic.io/ionicons/ionicons.designerpack.zip
- Extract the `/resources/` folder. Please follow [blade icons documentation](https://github.com/driesvints/blade-icons) for this.
- Update the `config/blade-icons.php` file accordingly.
- From the original source, all icons are in black color (they define color #000 for stroke and fill color). We need to change this so that the icon color can be changed from blade code. Using VS Code Find and Replace feature, do the following.

    | Find | Replace |
    | --- | ---- |
    | `stroke:#000;` | `stroke:currentColor;` |
    | `"#000"` | `"currentColor"` |