Given (/^user is on the main UI$/) do
	visit('http://localhost')
end

When(/^user clicks the account button$/)
	if(page.has_button?('add-account'))
		page.click_button('add-account')
	end
end

Then(/^user can create new account)
	expect(page).to have_selector('#new-account-diaglog', visible: true)
end
