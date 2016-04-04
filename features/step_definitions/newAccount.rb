Given (/^user is on UI$/) do
	visit('http://localhost')
end

When(/^user clicks the account button$/) do
	if(page.has_button?('add-account'))
		page.click_button('add-account')
	end
end

Then(/^user can create new account$/) do
	expect(page).to have_selector('#new-account-diaglog', visible: true)
end
