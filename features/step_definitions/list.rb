Given(/^user is on the main UI$/) do
end

Then(/^user should see a list$/) do
	expect(page).to have_selector('#account-module')
end
