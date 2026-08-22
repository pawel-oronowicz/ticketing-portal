import { useForm } from 'react-hook-form'
import { z } from 'zod'
import { zodResolver } from '@hookform/resolvers/zod'
import { signup } from '../../api/auth'
import { useNavigate } from 'react-router'
import { useAuth } from "../../context/AuthContext.tsx";
import { useSystemSettings } from "../../hooks/useSystemSettings.ts";

const schema = z.object({
    name: z.string().nonempty('Name must not be empty'),
    email: z.email('Invalid email'),
    password: z.string().min(8, 'Password must be at least 8 characters'),
    password_confirmation: z.string().min(8, 'Password must be at least 8 characters'),
}).refine((data) => data.password === data.password_confirmation, {
    message: "Passwords do not match",
    path: ["password_confirmation"],
});

type FormData = z.infer<typeof schema>

export default function RegisterPage() {
    const navigate = useNavigate()
    const { data: systemSettings, isLoading: systemSettingsLoading } = useSystemSettings()

    const {
        register,
        handleSubmit,
        formState: { errors, isSubmitting },
    } = useForm<FormData>({
        resolver: zodResolver(schema),
    })

    const { setAuth } = useAuth()

    if (systemSettingsLoading) return <p>Loading...</p>

    const onSubmit = async (data: FormData) => {
        const response = await signup(data)
        setAuth(response.user, response.token)
        navigate('/')
    }

    return (
        <div>
            <div className="flex max-w-7xl mx-auto px-6 py-6 justify-center">
                {systemSettings.registration_enabled ? (
                <form onSubmit={handleSubmit(onSubmit)}>
                    <fieldset className="fieldset bg-base-200 border-base-300 rounded-box w-xs border p-4">
                        <legend className="fieldset-legend">Register</legend>

                        <label className="label">Name</label>
                        <input
                            {...register('name')}
                            type="text"
                            className="input"
                            placeholder="Name"
                        />
                        {errors.name && <p className="text-red-500">{errors.name.message}</p>}

                        <label className="label mt-2">Email</label>
                        <input
                            {...register('email')}
                            type="email"
                            className="input"
                            placeholder="Email"
                        />
                        {errors.email && <p className="text-red-500">{errors.email.message}</p>}

                        <label className="label mt-2">Password</label>
                        <input
                            {...register('password')}
                            type="password"
                            className="input"
                            placeholder="Password"
                        />
                        {errors.password && <p className="text-red-500">{errors.password.message}</p>}

                        <label className="label mt-2">Confirm Password</label>
                        <input
                            {...register('password_confirmation')}
                            type="password"
                            className="input"
                            placeholder="Confirm Password"
                        />
                        {errors.password_confirmation && <p className="text-red-500">{errors.password_confirmation.message}</p>}

                        <button type="submit" disabled={isSubmitting} className="btn btn-neutral mt-4">
                            {isSubmitting ? 'Registering...' : 'Register'}
                        </button>
                    </fieldset>
                </form>
                ) : (
                    <p>Registration is currently disabled.</p>
                    )
                }
            </div>
        </div>
    )
}