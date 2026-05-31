# FloatingText

A premium, lightweight, and customizable floating text plugin for PocketMine-MP servers, featuring a state-of-the-art Bedrock Script API styled UI with dividers, labels, and submenus.

## Features
- **Dynamic Placeholders**: Supports placeholders like `{player}`, `{online}`, and `{max_online}` which update in real-time.
- **In-Game Forms UI**: Navigate through beautiful custom menus with dividers and clean color styling to manage all text entities.
- **Hybrid Commands**: Run commands directly with arguments (with tab-completion enum support) or use the interactive UI if no arguments are provided.
- **Persistent Storage**: Saves floating texts automatically in JSON format.
- **Multi-world Support**: Seamlessly teleports and displays floating texts across different worlds.

## Commands
All commands require the `floatingtext.command` permission (granted to OPs by default).
- `/floatingtext` (or `/ft`) - Opens the help menu.
- `/ft create` - Opens the form to create a new floating text.
- `/ft delete [id]` - Deletes the specified text, or opens the deletion form.
- `/ft edit [id]` - Edits the text message or position, or opens the selection form.
- `/ft tp [id]` - Teleports to the specified text, or opens a teleport menu.

## Installation
1. Download the plugin and place it into the `plugins/` directory of your server.
2. Restart the server.
3. Edit configurations inside `plugin_data/FloatingText/texts.yml` if you wish to change language settings or command prefixes.

## Shaded Libraries
This plugin includes a shaded version of [PMServerUI](https://github.com/DavyCraft648/PMServerUI) to render user interface components.
This plugin includes a shaded version of [SimplePacketHandler](https://github.com/Muqsit/SimplePacketHandler) to allow reading and handling of packets.
This plugin includes a shaded version of [Commando](https://github.com/Paroxity/Commando) to allow the usage & creation of hybrid commands.

## License
Licensed under the [MIT License](LICENSE).
