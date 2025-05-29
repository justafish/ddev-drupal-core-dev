setup() {
  set -eu -o pipefail

  TEST_BREW_PREFIX="$(brew --prefix 2>/dev/null || true)"
  export BATS_LIB_PATH="${BATS_LIB_PATH}:${TEST_BREW_PREFIX}/lib:/usr/lib/bats"
  bats_load_library bats-assert
  bats_load_library bats-file
  bats_load_library bats-support

  export DIR="$(cd "$(dirname "${BATS_TEST_FILENAME}")/.." >/dev/null 2>&1 && pwd)"
  export PROJNAME_COMPOSER="test-core-composer"
  export PROJNAME_CHECKOUT="test-corecheckout"
  mkdir -p ~/tmp

  export TESTDIR_CHECKOUT=$(mktemp -d ~/tmp/${PROJNAME_CHECKOUT}.XXXXXX)
  export TESTDIR_COMPOSER=$(mktemp -d ~/tmp/${PROJNAME_COMPOSER}.XXXXXX)

  export DDEV_NONINTERACTIVE=true
  export DDEV_NO_INSTRUMENTATION=true

  ddev delete -Oy ${PROJNAME_COMPOSER} >/dev/null 2>&1
  ddev delete -Oy ${PROJNAME_CHECKOUT} >/dev/null 2>&1

  composer create-project drupal/recommended-project ${TESTDIR_COMPOSER} --ignore-platform-reqs
  cd "${TESTDIR_COMPOSER}"
  run ddev config --project-name="${PROJNAME_COMPOSER}" --omit-containers=db --disable-settings-management
  assert_success
  run ddev start -y
  assert_success
  run ddev composer install
  run ddev composer require drupal/core-dev

  git clone --depth=1 https://git.drupalcode.org/project/drupal.git ${TESTDIR_CHECKOUT}
  cd "${TESTDIR_CHECKOUT}"
  run ddev config --project-name="${PROJNAME_CHECKOUT}" --omit-containers=db --disable-settings-management
  assert_success
  run ddev start -y
  assert_success
  run ddev composer install
}

health_checks() {
  cd "${TESTDIR_COMPOSER}"
  ddev exec "curl -s chrome:7900" | grep -q "noVNC"
  ddev phpunit web/core/tests/Drupal/Tests/Component/Datetime/DateTimePlusTest.php
  ddev nightwatch tests/Drupal/Nightwatch/Tests/loginTest.js

  cd "${TESTDIR_CHECKOUT}"
  ddev exec "curl -s chrome:7900" | grep -q "noVNC"
  ddev phpunit core/tests/Drupal/Tests/Component/Datetime/DateTimePlusTest.php
  ddev nightwatch tests/Drupal/Nightwatch/Tests/loginTest.js
}

teardown() {
  set -eu -o pipefail
  ddev delete -Oy ${PROJNAME_COMPOSER} >/dev/null 2>&1
  ddev delete -Oy ${PROJNAME_CHECKOUT} >/dev/null 2>&1
  [ "${TESTDIR_COMPOSER}" != "" ] && rm -rf ${TESTDIR_COMPOSER}
  [ "${TESTDIR_CHECKOUT}" != "" ] && rm -rf ${TESTDIR_CHECKOUT}
}

@test "install from directory" {
  set -eu -o pipefail

  cd ${TESTDIR_COMPOSER}
  echo "# ddev add-on get ${DIR} with project ${PROJNAME_COMPOSER} in $(pwd)" >&3
  run ddev add-on get "${DIR}"
  assert_success

  cd ${TESTDIR_CHECKOUT}
  echo "# ddev add-on get ${DIR} with project ${PROJNAME_CHECKOUT} in $(pwd)" >&3
  run ddev add-on get "${DIR}"
  assert_success

  health_checks
}
