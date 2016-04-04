Given(/^user is on the main UI$/) do
	visit("http://localhost:80")
end

Then(/^user should see a list$/) do
	expect(page).to have_selector('#account-module')
end
