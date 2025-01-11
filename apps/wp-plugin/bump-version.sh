#!/bin/bash

CURRENT_VERSION=$(node -p -e "require('./package.json').version")
CHANGE_TYPE=$1
if [ -n "$CHANGE_TYPE" ]; then
    LAST_MSG=$(git log -1 --pretty=%B . | cat)
    case $LAST_MSG in
      *"major"*) CHANGE_TYPE="major" ;;
      *"minor"*) CHANGE_TYPE="minor" ;;
      *) CHANGE_TYPE="patch" ;;
    esac
fi

echo "Current version: $CURRENT_VERSION"
yarn version $CHANGE_TYPE

NEXT_VERSION=$(node -p -e "require('./package.json').version")

echo "Bumping version to $NEXT_VERSION"

CURRENT_VERSION_ESCAPED=${CURRENT_VERSION//./\\.}
NEXT_VERSION_ESCAPED=${NEXT_VERSION//./\\.}

sed -i.bak "s/tag: $CURRENT_VERSION_ESCAPED/tag: $NEXT_VERSION_ESCAPED/g" README.txt && rm README.txt.bak
sed -i.bak "s/Version: $CURRENT_VERSION_ESCAPED/Version: $NEXT_VERSION_ESCAPED/g" selectika-wc.php && rm selectika-wc.php.bak
sed -i.bak "s/define('SLK_PLUGIN_VERSION', '$CURRENT_VERSION_ESCAPED/define('SLK_PLUGIN_VERSION', '$NEXT_VERSION_ESCAPED/g" selectika-wc.php && rm selectika-wc.php.bak


if [ -z "$CI" ]; then
  git config --global user.name "Bumpy Bot"
  git config --global user.email "admin@selectika.com"

  git commit -am "🔖 Bump WordPress plugin version to $NEXT_VERSION"
  git push
else
  echo "Skipping commit and push in CI environment"
  echo "Message: 🔖 Bump WordPress plugin version to $NEXT_VERSION"
fi
