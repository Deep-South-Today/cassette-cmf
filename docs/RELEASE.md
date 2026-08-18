# Release Process

This document describes how to create a new release of Cassette-CMF.

## Prerequisites

- All tests passing (`composer test`)
- All code standards passing (`composer cs:all`)
- PHPStan passing (`composer analyse`)
- Changes documented in `CHANGELOG.md`

## Version Numbering

Cassette-CMF follows [Semantic Versioning](https://semver.org/):

- **MAJOR** (X.0.0): Breaking changes
- **MINOR** (0.X.0): New features, backward compatible
- **PATCH** (0.0.X): Bug fixes, backward compatible

### Pre-release Versions

- Alpha: `v1.0.0-alpha.1`
- Beta: `v1.0.0-beta.1`
- Release Candidate: `v1.0.0-rc.1`

## Release Steps

### 1. Update CHANGELOG.md

Move items from `[Unreleased]` to a new version section:

```markdown
## [Unreleased]

## [1.0.0] - 2025-01-15

### Added
- New feature X
- New feature Y

### Changed
- Updated behavior of Z

### Fixed
- Bug fix A
```

Update the comparison links at the bottom:

```markdown
[Unreleased]: https://github.com/PedalCMS/cassette-cmf/compare/v1.0.0...HEAD
[1.0.0]: https://github.com/PedalCMS/cassette-cmf/compare/v0.0.2...v1.0.0
[0.0.2]: https://github.com/PedalCMS/cassette-cmf/releases/tag/v0.0.2
```

### 2. Commit Changes

The version number itself does **not** need updating by hand anywhere —
`bin/set-version.sh`, run automatically from a tag push (see step 4), writes
it into the README badge, `package.json`, and `composer.json` for you.

```bash
git add CHANGELOG.md
git commit -m "Prepare release v1.0.0"
git push origin main
```

### 3. Create and Push Tag

```bash
git tag -a v1.0.0 -m "Release v1.0.0"
git push origin v1.0.0
```

### 4. Automated Release

Pushing the tag triggers two workflows:

- **Sync Version From Tag** (`.github/workflows/version-sync.yml`) normalizes
  the tag (`v1.0.0` -> `1.0.0`), writes it into the README badge,
  `package.json`, and `composer.json` via `bin/set-version.sh`, and — if
  anything was out of sync — commits the fix back to `main` and re-points the
  tag at the corrected commit.
- **Release** (`.github/workflows/release.yml`) checks out the tag, re-runs
  `bin/set-version.sh` so the build always reflects the correct version even
  if version-sync hasn't landed yet, then:
  1. Validates the version format
  2. Installs production dependencies
  3. Creates distribution packages (`.zip` and `.tar.gz`)
  4. Extracts release notes from `CHANGELOG.md`
  5. Creates a GitHub Release with assets

### 5. Verify Release

1. Go to [Releases](https://github.com/PedalCMS/cassette-cmf/releases)
2. Verify the release notes are correct
3. Download and test the distribution packages
4. Confirm the README badge, `package.json`, and `composer.json` on `main` show the new version

## Quick Release Commands

```bash
# Run all checks
composer ci

# Create release
VERSION="1.0.0"
git add CHANGELOG.md
git commit -m "Prepare release v$VERSION"
git push origin main
git tag -a "v$VERSION" -m "Release v$VERSION"
git push origin "v$VERSION"
```

## Hotfix Process

For urgent bug fixes to a released version:

```bash
# Create hotfix branch from tag
git checkout -b hotfix/1.0.1 v1.0.0

# Make fixes, update CHANGELOG.md
git add .
git commit -m "Fix critical bug X"

# Merge to main
git checkout main
git merge hotfix/1.0.1
git push origin main

# Tag the release
git tag -a v1.0.1 -m "Release v1.0.1"
git push origin v1.0.1

# Clean up
git branch -d hotfix/1.0.1
```

## Troubleshooting

### Release workflow failed

1. Check the [Actions tab](https://github.com/PedalCMS/cassette-cmf/actions) for error details
2. Common issues:
   - Invalid version format (must be `vX.Y.Z`)
   - Missing CHANGELOG.md section for the version
   - Tests or checks failing

### Need to delete a release

```bash
# Delete the tag locally and remotely
git tag -d v1.0.0
git push origin :refs/tags/v1.0.0
```

Then delete the release from the GitHub UI.
