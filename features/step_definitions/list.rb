Given(/^user is on the main UI$/)
end

Then(/^user should see a list$/) do
	expect(page).to have_selector('#account-module', visible: true)
end