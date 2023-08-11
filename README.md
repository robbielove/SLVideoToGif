# Video to GIF Converter for Sign Language

This repository provides tools for converting videos into GIFs with embedded subtitles. The primary use case is for sign language videos, with the subtitles derived from the filenames of the videos.

## Features

- Convert videos in multiple formats to GIFs.
- Extract subtitles from associated subtitle files.
- Generate GIFs with subtitles based on video filenames.
- Customizable output directory structure.

## Usage

To use the command-line tool:

```bash
php artisan app:convert-video-to-gif [inputDirectory] [--outputDir=./output_gifs/signs]
```

Where:

- `inputDirectory` is the directory containing the videos you wish to convert.
- `--outputDir` is an optional argument specifying the output directory for the generated GIFs. The default is `./output_gifs/signs`.

## Setup

1. Clone this repository:
```bash
git clone [repository_url]
```

2. Navigate to the project directory and install dependencies:
```bash
composer install
```

3. Run the command as described in the usage section.

## Notes

- The subtitles for the sign language GIFs are derived from the video filenames. It's recommended to name the videos appropriately to ensure accurate subtitles.
- Ensure that the video filenames don't have any special characters or unnecessary numbers. The tool will clean the filenames to generate the subtitles.

## Contributing

Feel free to open issues or PRs if you find any bugs or have suggestions for improvements!

## License

SLVideoToGif is an open-source software licensed under the MIT license.

