{
    "name": "{{VENDOR}}/{{PLUGIN_NAME_KEBAB}}",
    "description": "{{PLUGIN_LABEL}}",
    "version": "1.0.0",
    "type": "shopware-platform-plugin",
    "license": "MIT",
    "authors": [
        {
            "name": "{{AUTHOR}}"
        }
    ],
    "require": {
        "shopware/core": "~6.5.0"
    },
    "extra": {
        "shopware-plugin-class": "{{NAMESPACE_JSON}}\\\\{{PLUGIN_NAME}}",
        "label": {
            "en-GB": "{{PLUGIN_LABEL}}",
            "de-DE": "{{PLUGIN_LABEL}}"
        }
    },
    "autoload": {
        "psr-4": {
            "{{NAMESPACE_JSON}}\\\\": "src/"
        }
    }
}
