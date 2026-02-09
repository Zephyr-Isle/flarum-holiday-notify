# Flarum Holiday Notify & Gray Mode Plugin

A Flarum extension that provides holiday notifications using AI-generated content and gray mode for memorial days.

## Features

- **Gray Mode**: Automatically applies grayscale filter to the forum on Memorial Days (Dec 13, Sep 18), excluding user avatars.
- **Holiday Notifications**: Sends notifications to all users on major holidays (Gregorian & Lunar).
- **AI Integration**: Generates unique holiday greetings using OpenAI (GPT-3.5/4).
- **Admin Panel**: Fully configurable holidays, independent switches, and OpenAI settings.
- **Customizable**: Add custom holidays, support for custom templates.

## Installation

1.  Unzip the package into `packages/trae-flarum-holiday-notify` (or your preferred path).
2.  Add the repository to your Flarum `composer.json`:

    ```json
    "repositories": [
        {
            "type": "path",
            "url": "packages/trae-flarum-holiday-notify"
        }
    ]
    ```

3.  Install the package:

    ```bash
    composer require trae/flarum-holiday-notify
    ```

    *Note: This will automatically install `overtrue/chinese-calendar`.*

4.  Enable the extension in the Admin Panel.

## Configuration

1.  Go to **Admin > Holiday Notify**.
2.  Configure OpenAI API Key and URL (optional, required for AI generation).
3.  Enable/Disable specific holidays.
4.  Add custom holidays if needed.

## Scheduler

To ensure notifications are sent automatically, you must have the Flarum scheduler running. Add this to your crontab:

```bash
* * * * * php /path/to/flarum schedule:run >> /dev/null 2>&1
```

The holiday check runs daily at 08:00.

## Development

### Frontend

```bash
cd js
npm install
npm run dev
```

### Backend

To run tests:

```bash
vendor/bin/phpunit
```

## Technical Details

- **Frontend**: TypeScript/JavaScript (Admin interface), CSS Filters (Gray Mode).
- **Backend**: PHP, MySQL, Queue (for mass notifications).
- **AI**: OpenAI API integration.

## License

MIT
